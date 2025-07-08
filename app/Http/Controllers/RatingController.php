<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\RatingImport;
use App\Models\Rating;
use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class RatingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua ratings + nama pembeli + nama produk
        $allRatings = DB::table('ratings')
            ->join('pembelis', 'ratings.pembeli_id', '=', 'pembelis.id')
            ->join('produks', 'ratings.produk_id', '=', 'produks.id')
            ->select(
                'ratings.id',
                'ratings.pembeli_id',
                'ratings.produk_id',
                'ratings.rating',
                'pembelis.name as nama_pembeli'
            )
            ->when($search, function ($query, $search) {
                return $query->where('pembelis.name', 'like', "%$search%");
            })
            ->orderBy('ratings.id', 'asc')
            ->get();

        // Ambil daftar produk dari database (bukan dari ratings)
        $produks = DB::table('produks')->orderBy('id')->pluck('nama_produk', 'id');

        // Buat daftar pembeli unik
        $pembeliMap = [];
        foreach ($allRatings as $r) {
            if (!isset($pembeliMap[$r->pembeli_id])) {
                $pembeliMap[$r->pembeli_id] = $r->nama_pembeli;
            }
        }

        // Pagination manual untuk pembeli
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $pagedPembeliIds = array_slice(array_keys($pembeliMap), ($currentPage - 1) * $perPage, $perPage, true);
        $pagedPembeliMap = array_intersect_key($pembeliMap, array_flip($pagedPembeliIds));

        $paginator = new LengthAwarePaginator(
            collect($pagedPembeliMap),
            count($pembeliMap),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        // Buat matriks rating
        $ratingMatrix = [];
        foreach ($allRatings as $r) {
            if (isset($pagedPembeliMap[$r->pembeli_id])) {
                $ratingMatrix[$r->pembeli_id][$r->produk_id] = $r->rating;
            }
        }

        return view('pages.rating.index', [
            'pembelis' => $paginator,
            'produks' => $produks, // urutan produk tetap
            'ratingMatrix' => $ratingMatrix,
            'search' => $search
        ]);
    }


    public function create()
    {
        $pembelis = Pembeli::all();
        $produks = Produk::all();

        return view('pages.rating.create', compact('pembelis', 'produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pembeli_id' => 'required|exists:pembelis,id',
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::create($validated);

        return redirect('/rating')->with('success', 'Rating berhasil ditambahkan.');
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ratings.*' => 'nullable|integer|between:1,5',
        ]);

        $pembeli = Pembeli::findOrFail($id);
        $pembeli->name = $request->nama;
        $pembeli->save();

        // Simpan rating baru
        foreach ($request->ratings as $produk_id => $rating) {
            if ($rating !== null) {
                Rating::updateOrCreate(
                    ['pembeli_id' => $id, 'produk_id' => $produk_id],
                    ['rating' => $rating]
                );
            }
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Hapus semua rating pembeli ini
        Rating::where('pembeli_id', $id)->delete();

        // Hapus pembelinya
        $pembeli = Pembeli::findOrFail($id);
        $pembeli->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $import = new RatingImport;
            Excel::import($import, $request->file('file'));

            if (count($import->errors) > 0) {
                $duplikat = implode(', ', $import->errors);
                return redirect('/rating')->with('error', 'Beberapa data tidak diimport karena sudah ada: ' . $duplikat);
            }

            return redirect('/rating')->with('success', 'Data rating berhasil diimport.');
        } catch (\Exception $e) {
            return redirect('/rating')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
