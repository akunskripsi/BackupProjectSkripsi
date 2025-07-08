<?php

namespace App\Imports;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Cek apakah kode_produk sudah ada
            $exists = Produk::where('kode_produk', $row['kode_produk'])->first();

            if ($exists) {
                $this->errors[] = $row['kode_produk'];
                continue;
            }

            Produk::create([
                'kode_produk' => $row['kode_produk'],
                'nama_produk' => $row['nama_produk'],
                'kategori' => $row['kategori'],
                'harga' => preg_replace('/\D/', '', $row['harga']),
            ]);
        }
    }
}
