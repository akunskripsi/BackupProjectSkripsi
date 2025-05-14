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
    public function collection(Collection $rows)
    {
        // Lewati baris header
        $rows->shift();

        foreach ($rows as $row) {
            $kode_pembeli = trim($row[0]);
            $kode_produk = trim($row[1]);
            $rating = intval($row[2]);

            // Cari ID pembeli dari kode
            $pembeli = Pembeli::where('kode_pembeli', $kode_pembeli)->first();
            $produk = Produk::where('kode_produk', $kode_produk)->first();

            if ($pembeli && $produk) {
                Rating::updateOrCreate(
                    [
                        'pembeli_id' => $pembeli->id,
                        'produk_id' => $produk->id,
                    ],
                    [
                        'rating' => $rating,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
