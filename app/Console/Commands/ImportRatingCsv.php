<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRatingCsv extends Command
{
    protected $signature = 'import:rating';
    protected $description = 'Import data rating dari file CSV menggunakan kode pembeli dan kode produk';

    public function handle()
    {
        $path = storage_path('app/data_rating.csv');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: data_rating.csv");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // baca baris header: UserID,kode_produk,Rating

        while (($row = fgetcsv($file)) !== false) {
            $kodePembeli = $row[0];
            $kodeProduk  = $row[1];
            $rating      = $row[2];

            $pembeli = DB::table('pembelis')->where('kode_pembeli', $kodePembeli)->first();
            $produk  = DB::table('produks')->where('kode_produk', $kodeProduk)->first();

            if ($pembeli && $produk) {
                DB::table('ratings')->insert([
                    'pembeli_id' => $pembeli->id,
                    'produk_id'  => $produk->id,
                    'rating'     => $rating,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->warn("Data tidak ditemukan: Pembeli = $kodePembeli, Produk = $kodeProduk");
            }
        }

        fclose($file);
        $this->info("Import data rating selesai!");
    }
}
