<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembeli_id',
        'kode_produk',
        'nama_produk',
        'harga',
        'rating_prediksi',
    ];
}
