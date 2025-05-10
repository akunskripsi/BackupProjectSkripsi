<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasis';

    protected $guarded = [];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'kode_pembeli', 'kode_pembeli');
    }


    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kode_produk', 'kode_produk');
    }
}
