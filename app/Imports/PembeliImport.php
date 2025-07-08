<?php

namespace App\Imports;

use App\Models\Pembeli;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;

class PembeliImport implements ToCollection, WithHeadingRow
{
    protected $duplicates = [];

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

        // Simpan ke session agar bisa diakses di controller
        if (count($this->duplicates) > 0) {
            Session::flash('duplicate_data', $this->duplicates);
        }
    }
}
