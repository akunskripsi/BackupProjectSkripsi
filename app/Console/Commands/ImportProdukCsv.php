<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProdukCsv extends Command
{
    protected $signature = 'import:produk';
    protected $description = 'Import data produk dari file CSV';

    public function handle()
    {
        $path = storage_path('app/data_produk.csv'); // pastikan nama file sesuai

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: data_produk.csv");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // lewati baris header

        $jumlah = 0;
        while (($row = fgetcsv($file)) !== false) {
            DB::table('produks')->insert([
                'kode_produk' => $row[0],
                'nama_produk' => $row[1],
                'kategori'    => $row[2],
                'harga'       => $row[3],
                'stok'        => $row[4],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $jumlah++;
        }

        fclose($file);
        $this->info("Import selesai! Total produk diimpor: {$jumlah}");
    }
}
