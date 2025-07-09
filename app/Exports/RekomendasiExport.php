<?php

namespace App\Exports;

use App\Models\Rekomendasi;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekomendasiExport implements FromView
{
    protected $pembeliId;
    protected $kategori;

    public function __construct($pembeliId = null, $kategori = null)
    {
        $this->pembeliId = $pembeliId;
        $this->kategori = $kategori;
    }

    public function view(): View
    {
        $query = Rekomendasi::query();

        if ($this->pembeliId) {
            $query->where('pembeli_id', $this->pembeliId);
        }

        if ($this->kategori) {
            $produkIds = Produk::where('kategori', $this->kategori)->pluck('kode_produk');
            $query->whereIn('kode_produk', $produkIds);
        }

        $data = $query->get();

        return view('exports.rekomendasi', [
            'rekomendasis' => $data
        ]);
    }
}
