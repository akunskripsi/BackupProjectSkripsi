<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $guarded = [];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
