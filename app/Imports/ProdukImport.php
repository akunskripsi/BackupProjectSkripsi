<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Produk([
            'kode_produk' => $row['kode_produk'],
            'nama_produk' => $row['nama_produk'],
            'kategori' => $row['kategori'],
            'harga' => preg_replace('/\D/', '', $row['harga']), // hilangkan karakter selain angka
            'stok' => $row['stok'],
        ]);
    }
}