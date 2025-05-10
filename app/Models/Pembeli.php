<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'Pembelis';

    protected $guarded = [];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
