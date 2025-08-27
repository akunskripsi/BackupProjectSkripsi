<?php

namespace App\Imports;

use App\Models\Pembeli;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PembeliImport implements ToCollection, WithHeadingRow
{
    public $duplicates = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $kode = $row['kode_pembeli'];

            if (Pembeli::where('kode_pembeli', $kode)->exists()) {
                $this->duplicates[] = $kode;
                continue;
            }

            Pembeli::create([
                'kode_pembeli' => $kode,
                'name' => $row['name'],
                'lokasi' => $row['lokasi'],
            ]);
        }
    }
}
