<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\PembeliImport;
use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembeli::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('lokasi', 'like', '%' . $search . '%');
        }

        $pembelis = $query->get();

        return view('pages.pembeli.index', [
            'pembeli' => $pembelis,
        ]);
    }

    public function create()
    {
        // Ambil kode terakhir
        $lastPembeli = Pembeli::orderBy('kode_pembeli', 'desc')->first();

        if ($lastPembeli && preg_match('/U(\d+)/', $lastPembeli->kode_pembeli, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = $lastNumber + 1;
            $newKodePembeli = 'U' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // Misal U0801
        } else {
            $newKodePembeli = 'U0001'; // Jika belum ada data, mulai dari U0001
        }

        return view('pages.pembeli.create', compact('newKodePembeli'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pembeli' => 'required|unique:pembelis,kode_pembeli', // Pastikan kode unik
            'name' => ['required', 'max:100'],
            'lokasi' => ['required', 'max:20'],
        ]);

        Pembeli::create([
            'kode_pembeli' => $validatedData['kode_pembeli'], // Simpan kode pembeli
            'name' => $validatedData['name'],
            'lokasi' => $validatedData['lokasi'],
        ]);

        return redirect('/pembeli')->with('success', 'Berhasil menambahkan data');
    }


    public function edit($id)
    {
        $pembeli = Pembeli::findOrFail($id);

        return view('pages.pembeli.edit', [
            'pembeli' => $pembeli,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'lokasi' => ['required', 'max:20'],
        ]);

        Pembeli::findOrFail($id)->update($validatedData);

        return redirect('/pembeli')->with('success', 'Berhasil mengubah data');
    }

    public function destroy($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        $pembeli->delete();

        return redirect('/pembeli')->with('success', 'Berhasil menghapus data');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $import = new PembeliImport();
            Excel::import($import, $request->file('file'));

            if (count($import->duplicates) > 0) {
                $duplicates = implode(', ', $import->duplicates);
                return redirect('/pembeli')->with('error', 'Data tidak diimport karena sudah ada: ' . $duplicates);
            }

            return redirect('/pembeli')->with('success', 'Data Pembeli berhasil diimport.');
        } catch (\Exception $e) {
            return redirect('/pembeli')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
