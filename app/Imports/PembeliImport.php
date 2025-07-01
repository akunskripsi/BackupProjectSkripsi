<?php

namespace App\Imports;

use App\Models\Pembeli;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PembeliImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pembeli([
            'kode_pembeli' => $row['kode_pembeli'],
            'name' => $row['name'],
            'lokasi' => $row['lokasi'],
        ]);
    }
}
