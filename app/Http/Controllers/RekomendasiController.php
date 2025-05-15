<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Pembeli;
use App\Models\Produk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache; // Tambahkan

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID pembeli dari Rating untuk dropdown
        $pembeliIds = Rating::select('pembeli_id')
            ->distinct()
            ->orderBy('id')
            ->pluck('pembeli_id')
            ->toArray();

        // Ambil data pembeli sesuai urutan
        $pembelis = Pembeli::whereIn('id', $pembeliIds)
            ->get()
            ->sortBy(function ($p) use ($pembeliIds) {
                return array_search($p->id, $pembeliIds);
            })
            ->values();

        $produk = Produk::all();
        $rekomendasis = collect();
        $similarUsers = collect();
        $selectedUserId = $request->pembeli_id;

        if ($selectedUserId) {
            session(['selected_user' => $selectedUserId]);

            // Clear cache untuk pembeli yang dipilih
            Cache::forget('rekomendasi_user_' . $selectedUserId);
            Cache::forget('similar_users_' . $selectedUserId);

            $ratings = Rating::all();
            $ratingMatrix = [];

            foreach ($ratings as $rating) {
                $ratingMatrix[$rating->pembeli_id][$rating->produk_id] = $rating->rating;
            }

            if (!isset($ratingMatrix[$selectedUserId])) {
                session()->flash('warning', 'Pembeli ini belum memberikan rating.');
                return view('pages.rekomendasi.index', compact('pembelis', 'produk', 'rekomendasis', 'similarUsers'));
            }

            // Hitung rekomendasi tanpa cache untuk memastikan data fresh
            [$rekomendasis, $similarUsers] = $this->recommendProducts($selectedUserId, $ratingMatrix, $produk);
        }

        return view('pages.rekomendasi.index', compact('pembelis', 'produk', 'rekomendasis', 'similarUsers', 'selectedUserId'));
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

        $allPembeli = Pembeli::whereIn('id', array_keys($similarities))->get()->keyBy('id');

        // Similar Users
        $similarUsers = collect();
        foreach ($similarities as $id => $sim) {
            $pembeli = $allPembeli[$id] ?? null;
            $similarUsers->push((object)[
                'pembeli_id' => $id,
                'kode' => $pembeli?->kode_pembeli ?? '-',
                'nama' => $pembeli?->name ?? 'Tidak Dikenal',
                'similarity' => round($sim, 4)
            ]);
        }

        $similarUsers = $similarUsers->sortByDesc('similarity');
        //Jika hasil similarUsers semuanya 0 (tidak mirip), tampilkan notifikasi
        if ($similarUsers->where('similarity', '>', 0)->isEmpty()) {
            session()->flash('warning', 'Tidak ditemukan pembeli yang mirip.');
        }

        // Produk yang belum dirating
        $userRated = $ratingMatrix[$userId] ?? [];
        $unratedProducts = $produks->filter(fn($p) => !isset($userRated[$p->id]));

        $predictions = [];

        foreach ($unratedProducts as $produk) {
            $totalSim = 0;
            $weightedSum = 0;
            $perKontributor = [];

            foreach ($similarities as $otherUser => $sim) {
                if (isset($ratingMatrix[$otherUser][$produk->id])) {
                    $rating = $ratingMatrix[$otherUser][$produk->id];
                    $perKontributor[] = [
                        'pembeli_id' => $otherUser,
                        'nama' => $allPembeli[$otherUser]?->name ?? '-',
                        'similarity' => round($sim, 4),
                        'rating' => $rating,
                        'contribution' => round($sim * $rating, 4)
                    ];

                    $weightedSum += $sim * $rating;
                    $totalSim += $sim;
                }
            }

            if ($totalSim > 0) {
                $predictedRating = $weightedSum / $totalSim;

                if ($predictedRating > 0) {
                    $predictions[] = (object)[
                        'kode_produk' => $produk->kode_produk,
                        'nama' => $produk->nama_produk,
                        'kategori' => $produk->kategori,
                        'harga' => $produk->harga,
                        'predicted_rating' => $predictedRating,
                        'details' => $perKontributor,
                        'total_similarity' => round($totalSim, 4),
                        'total_weighted' => round($weightedSum, 4),
                    ];
                }
            }
        }

        usort($predictions, fn($a, $b) => $b->predicted_rating <=> $a->predicted_rating);
        $rekomendasi = collect(array_slice($predictions, 0, $topN));

        // Tambahkan caching di sini, sebelum return
        $cacheKey = 'rekomendasi_user_' . $userId;
        $cacheSimKey = 'similar_users_' . $userId;

        Cache::put($cacheKey, $rekomendasi, now()->addMinutes(10));
        Cache::put($cacheSimKey, $similarUsers, now()->addMinutes(10));

        return [$rekomendasi, $similarUsers];
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

    public function show($id)
    {
        $pembeliTerpilih = session('selected_user');

        $targetUserId = $id;

        // Ambil semua rating
        $ratings = Rating::all();
        $ratingMatrix = [];

        foreach ($ratings as $rating) {
            $ratingMatrix[$rating->pembeli_id][$rating->produk_id] = $rating->rating;
        }

        if (!$pembeliTerpilih) {
            return redirect()->route('rekomendasi.index')->with('warning', 'Silakan pilih pembeli terlebih dahulu.');
        }

        $vecA = $ratingMatrix[$pembeliTerpilih];
        $vecB = $ratingMatrix[$targetUserId];

        $dotProduct = 0;
        $magnitudeA = 0;
        $magnitudeB = 0;
        $details = [];

        $allKeys = array_unique(array_merge(array_keys($vecA), array_keys($vecB)));

        foreach ($allKeys as $key) {
            $a = $vecA[$key] ?? 0;
            $b = $vecB[$key] ?? 0;
            $dotProduct += $a * $b;
            $magnitudeA += $a ** 2;
            $magnitudeB += $b ** 2;

            $produk = Produk::find($key);
            $details[] = [
                'produk_id' => $key,
                'nama_produk' => $produk?->nama_produk ?? 'Tidak Diketahui',
                'rating_A' => $a,
                'rating_B' => $b,
                'a_x_b' => $a * $b
            ];
        }

        $similarity = 0;
        if ($magnitudeA != 0 && $magnitudeB != 0) {
            $similarity = $dotProduct / (sqrt($magnitudeA) * sqrt($magnitudeB));
        }

        return view('pages.rekomendasi.show', [
            'details' => $details,
            'dotProduct' => $dotProduct,
            'magnitudeA' => sqrt($magnitudeA),
            'magnitudeB' => sqrt($magnitudeB),
            'similarity' => $similarity
        ]);
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
