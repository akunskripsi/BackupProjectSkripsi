<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\ProdukImport;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_produk', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');
        }

        $produks = $query->get();

        return view('pages.produk.index', [
            'produk' => $produks,
        ]);
    }


    public function create()
    {
        // Ambil kode produk terakhir
        $lastProduk = Produk::orderBy('kode_produk', 'desc')->first();

        if ($lastProduk) {
            // Ambil nomor terakhir setelah "PRD-" dan diubah ke angka
            preg_match('/PRD-(\d{4})/', $lastProduk->kode_produk, $matches);
            $lastNumber = (int)$matches[1]; // Mengambil angka setelah "PRD-"
            $nextNumber = $lastNumber + 1;
            $newKodeProduk = 'PRD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // Format PRD-0101, PRD-0102, dll
        } else {
            // Jika belum ada data, mulai dari PRD-0101
            $newKodeProduk = 'PRD-0101';
        }

        return view('pages.produk.create', compact('newKodeProduk'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_produk' => 'required|unique:produks,kode_produk', // Pastikan kode produk unik
            'nama_produk' => ['required', 'max:100'],
            'kategori' => ['required', 'max:50'],
            'harga' => ['required', 'max:20'],
        ]);

        Produk::create([
            'kode_produk' => $validatedData['kode_produk'], // Simpan kode produk
            'nama_produk' => $validatedData['nama_produk'],
            'kategori' => $validatedData['kategori'],
            'harga' => $validatedData['harga'],
        ]);

        return redirect('/produk')->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id); // ✅ fix here

        return view('pages.produk.edit', [
            'produk' => $produk,
        ]);

        // return view('pages.produk.edit');
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_produk' => ['required', 'max:100'],
            'kategori' => ['required', 'max:50'],
            'harga' => ['required', 'max:20'],
        ]);

        Produk::findOrFail($id)->update($validatedData); // ✅ fix here


        return redirect('/produk')->with('success', 'Berhasil mengubah data');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id); // ✅ fix here
        $produk->delete();

        return redirect('/produk')->with('success', 'Berhasil menghapus data');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new ProdukImport, $request->file('file'));
            return redirect('/produk')->with('success', 'Data produk berhasil diimport.');
        } catch (\Exception $e) {
            return redirect('/produk')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
