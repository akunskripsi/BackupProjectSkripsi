<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportPembeliCsv extends Command
{
    protected $signature = 'import:pembeli';
    protected $description = 'Import data pembeli dari file CSV';

    public function handle()
    {
        $path = storage_path('app/data_pembeli.csv');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: data_pembeli.csv");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // baris header: name,email,lokasi

        while (($row = fgetcsv($file)) !== false) {
            DB::table('pembelis')->insert([
                'kode_pembeli' => $row[0],
                'name' => $row[1],
                'email' => $row[2],
                'lokasi' => $row[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
        $this->info("Import data pembeli selesai!");
    }
}
