<?php

namespace App\Imports;

use App\Models\Rating;
use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class RatingImport implements ToCollection
{
    public $errors = []; // untuk menyimpan duplikat

    public function collection(Collection $rows)
    {
        $rows->shift(); // skip header

        foreach ($rows as $row) {
            $kode_pembeli = trim($row[0]);
            $kode_produk = trim($row[1]);
            $rating = intval($row[2]);

            $pembeli = Pembeli::where('kode_pembeli', $kode_pembeli)->first();
            $produk = Produk::where('kode_produk', $kode_produk)->first();

            if ($pembeli && $produk) {
                // Cek apakah kombinasi pembeli-produk sudah ada
                $existing = Rating::where('pembeli_id', $pembeli->id)
                    ->where('produk_id', $produk->id)
                    ->exists();

                if ($existing) {
                    // simpan info error duplikat
                    $this->errors[] = $kode_pembeli . ' - ' . $kode_produk;
                    continue;
                }

                Rating::create([
                    'pembeli_id' => $pembeli->id,
                    'produk_id' => $produk->id,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
