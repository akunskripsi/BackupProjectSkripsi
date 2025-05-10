<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Support\Collection;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil urutan unik pembeli sesuai urutan kemunculan di tabel rating
        $pembeliIds = Rating::select('pembeli_id')
            ->distinct()
            ->orderBy('id') // urut berdasarkan ID rating (urutan input)
            ->pluck('pembeli_id')
            ->toArray();

        // Ambil data pembeli dengan urutan yang sama seperti rating
        $pembelis = Pembeli::whereIn('id', $pembeliIds)
            ->get()
            ->sortBy(function ($p) use ($pembeliIds) {
                return array_search($p->id, $pembeliIds);
            })
            ->values();

        $produk = Produk::all();
        $rekomendasis = collect();
        $similarUsers = collect(); // Untuk daftar pembeli mirip dan skor

        if ($request->has('pembeli_id') && $request->pembeli_id != '') {
            // Ambil rating semua pembeli
            $ratings = Rating::all();
            $ratingMatrix = [];

            foreach ($ratings as $rating) {
                $ratingMatrix[$rating->pembeli_id][$rating->produk_id] = $rating->rating;
            }

            // Ambil pembeli yang dipilih
            $userId = $request->pembeli_id;

            if (isset($ratingMatrix[$userId])) {
                [$rekomendasis, $similarUsers] = $this->recommendProducts($userId, $ratingMatrix, $produk);
            } else {
                session()->flash('warning', 'Pembeli ini belum memberikan rating.');
            }
        }

        return view('pages.rekomendasi.index', [
            'pembelis' => $pembelis,
            'produk' => $produk,
            'rekomendasis' => $rekomendasis,
            'similarUsers' => $similarUsers
        ]);
    }


    // Function to recommend products using cosine similarity
    private function recommendProducts($userId, $ratingMatrix, $produks, $topN = 5)
    {
        $similarities = [];

        foreach ($ratingMatrix as $otherUserId => $ratings) {
            if ($otherUserId == $userId) continue;
            $similarity = $this->cosineSimilarity($ratingMatrix[$userId], $ratings);
            $similarities[$otherUserId] = $similarity;
        }

        // Simpan daftar pengguna mirip
        $similarUsers = collect();
        foreach ($similarities as $id => $sim) {
            $pembeli = Pembeli::find($id);
            $nama = $pembeli?->name ?? 'Tidak Dikenal';
            $kode = $pembeli?->kode_pembeli ?? '-';
            $similarUsers->push((object)[
                'pembeli_id' => $id,
                'kode' => $kode,
                'nama' => $nama,
                'similarity' => round($sim, 4)
            ]);
        }

        // Urutkan berdasarkan similarity terbesar
        $similarUsers = $similarUsers->sortByDesc('similarity');

        $userRated = $ratingMatrix[$userId] ?? [];
        $unratedProducts = $produks->filter(fn($p) => !isset($userRated[$p->id]));

        $predictions = [];
        foreach ($unratedProducts as $produk) {
            $totalSim = 0;
            $weightedSum = 0;

            foreach ($similarities as $otherUser => $sim) {
                if (isset($ratingMatrix[$otherUser][$produk->id])) {
                    $weightedSum += $sim * $ratingMatrix[$otherUser][$produk->id];
                    $totalSim += $sim;
                }
            }

            if ($totalSim > 0) {
                $predictedRating = round($weightedSum / $totalSim, 2);

                if ($predictedRating > 0) {
                    $predictions[] = (object)[
                        'kode_produk' => $produk->kode_produk,
                        'nama' => $produk->nama_produk,
                        'kategori' => $produk->kategori,
                        'harga' => $produk->harga,
                        'predicted_rating' => $predictedRating,
                    ];
                }
            }
        }

        usort($predictions, fn($a, $b) => $b->predicted_rating <=> $a->predicted_rating);

        return [collect(array_slice($predictions, 0, $topN)), $similarUsers];
    }



    // Function to calculate cosine similarity
    private function cosineSimilarity($vecA, $vecB)
    {
        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;

        $allKeys = array_unique(array_merge(array_keys($vecA), array_keys($vecB)));

        foreach ($allKeys as $key) {
            $a = $vecA[$key] ?? 0;
            $b = $vecB[$key] ?? 0;

            $dotProduct += $a * $b;
            $magnitudeA += $a ** 2;
            $magnitudeB += $b ** 2;
        }

        if ($magnitudeA == 0 || $magnitudeB == 0) return 0;

        return $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
    }


    public function create()
    {
        return view('pages.rekomendasi.index');
    }

    public function store(Request $request) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
