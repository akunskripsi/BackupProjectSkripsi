@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perhitungan Rekomendasi</h1>
    </div>

    {{-- Tables --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('rekomendasi.index') }}">
                        <div class="form-group">
                            <label for="pembeli_id">Pilih Pembeli</label>
                            <select class="form-control select2" id="pembeli_id" name="pembeli_id"
                                onchange="this.form.submit()">
                                <option value="">-- Pilih Pembeli --</option>
                                @foreach ($pembelis as $pembeli)
                                    <option value="{{ $pembeli->id }}"
                                        {{ request('pembeli_id') == $pembeli->id ? 'selected' : '' }}>
                                        {{ $pembeli->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="kategori">Pilih Kategori Produk</label>
                            <select class="form-control select2" id="kategori" name="kategori"
                                onchange="this.form.submit()">
                                <option value="">-- Semua Kategori --</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori }}"
                                        {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if (isset($rekomendasis) && count($rekomendasis) > 0)
                        @if (isset($notifikasi))
                            <div class="alert alert-warning">
                                {{ $notifikasi }}
                            </div>
                        @endif

                        @if (isset($similarUsers) && $similarUsers->count() > 0)
                            <h5 class="mt-4">Perhitungan Cosine Similarity</h5>
                            <div style="max-height: 400px; overflow-y: auto;"> <!-- Fitur Scroll -->
                                <table class="table table-bordered">
                                    <thead class="bg-danger text-white sticky-top">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kode Pembeli</th>
                                            <th>Nama Pembeli</th>
                                            <th>Skor Cosine Similarity</th>
                                            <th>Lihat Perhitungan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($similarUsers as $sim)
                                            <tr class="text-center">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sim->kode }}</td>
                                                <td>{{ $sim->nama }}</td>
                                                <td>{{ $sim->similarity }}</td>
                                                <td>
                                                    <a href="{{ route('rekomendasi.show', [
                                                        'id' => $sim->pembeli_id,
                                                        'selected' => $pembeliId,
                                                        'kategori' => request('kategori'),
                                                    ]) }}"
                                                        class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- End Fitur Scroll -->
                        @endif
                    @else
                        <p class="text-center">Pilih pembeli untuk melihat rekomendasi</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                        <a href="{{ route('rekomendasi.export', ['pembeli_id' => request('pembeli_id'), 'kategori' => request('kategori')]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                    </div>

                    <h5 class="mt-4">Daftar Hasil Rekomendasi</h5>
                    <div id="accordionPrediksi"> {{-- Pembungkus Accordion --}}
                        <table class="table table-bordered table-hovered">
                            <thead class="bg-success text-white sticky-top">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Rating Prediksi</th>
                                    <th>Detail Perhitungan Rating Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekomendasis as $item)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->predicted_rating, 1) }}</td>
                                        <td>
                                            <!-- Button untuk collapse detail -->
                                            <button class="btn btn-sm btn-secondary" type="button" data-toggle="collapse"
                                                data-target="#detail-{{ $loop->iteration }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse multi-collapse" id="detail-{{ $loop->iteration }}"
                                        data-parent="#accordionPrediksi">
                                        <td colspan="6">
                                            @if ($item->total_similarity == 0 && isset($item->rating_details))
                                                <strong>Perhitungan Rating Rata-Rata:</strong><br>
                                                <p>Produk ini memiliki {{ $item->rating_details['count'] }} rating:</p>
                                                <ul>
                                                    @foreach ($item->rating_details['ratings'] as $rating)
                                                        <li>Pembeli {{ $rating->pembeli->name }}: {{ $rating->rating }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <p>
                                                    <strong>Total Rating:</strong>
                                                    {{ $item->rating_details['total_rating'] }}<br>
                                                    <strong>Jumlah Rating:</strong>
                                                    {{ $item->rating_details['count'] }}<br>
                                                    <strong>Rata-rata:</strong> {{ $item->rating_details['total_rating'] }}
                                                    ÷ {{ $item->rating_details['count'] }}
                                                    = {{ number_format($item->rating_details['average'], 4) }}
                                                </p>
                                            @else
                                                <strong>Perhitungan Prediksi Rating:</strong><br>
                                                <ul>
                                                    @foreach ($item->details as $d)
                                                        <li>
                                                            {{ $loop->iteration }}. {{ $d['nama'] }} (similarity ×
                                                            rating):
                                                            ({{ $d['similarity'] }}
                                                            × {{ $d['rating'] }})
                                                            = {{ $d['contribution'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <p>
                                                    <strong>Total Weighted Sum =</strong>
                                                    @php
                                                        $weightedParts = [];
                                                        foreach ($item->details as $d) {
                                                            $weightedParts[] = $d['similarity'] . ' × ' . $d['rating'];
                                                        }
                                                    @endphp
                                                    {{ implode(' + ', $weightedParts) }} = {{ $item->total_weighted }}
                                                </p>
                                                <p>
                                                    <strong>Total Similarity =</strong>
                                                    @php
                                                        $similarityParts = [];
                                                        foreach ($item->details as $d) {
                                                            $similarityParts[] = $d['similarity'];
                                                        }
                                                    @endphp
                                                    {{ implode(' + ', $similarityParts) }} = {{ $item->total_similarity }}
                                                </p>
                                                @if ($item->total_similarity == 0)
                                                    <p><strong>Prediksi Rating menggunakan rata-rata semua rating produk
                                                            ini</strong></p>
                                                    <p>
                                                        Rata-rata rating produk =
                                                        {{ number_format($item->predicted_rating, 4) }}
                                                    </p>
                                                @else
                                                    <p>
                                                        <strong>Prediksi Rating =</strong>
                                                        {{ number_format($item->total_weighted, 4) }} ÷
                                                        {{ number_format($item->total_similarity, 4) }} =
                                                        {{ number_format($item->predicted_rating, 4) }}
                                                    </p>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> {{-- Tutup accordion --}}
                </div>
            </div>
        </div>
    </div>
@endsection
