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
                            <select class="form-control" id="pembeli_id" name="pembeli_id" onchange="this.form.submit()">
                                <option value="">-- Pilih Pembeli --</option>
                                @foreach ($pembelis as $pembeli)
                                    <option value="{{ $pembeli->id }}"
                                        {{ request('pembeli_id') == $pembeli->id ? 'selected' : '' }}>
                                        {{ $pembeli->name }}
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

                        <h5 class="mt-4">Daftar Hasil Rekomendasi</h5>
                        <div id="accordionPrediksi"> {{-- Pembungkus Accordion --}}
                            <table class="table table-bordered table-hovered">
                                <thead class="bg-success text-white sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Produk</th>
                                        <th>Rating Prediksi</th>
                                        <th class="text-center">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekomendasis as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ number_format($item->predicted_rating, 4) }}</td>
                                            <td class="text-center">
                                                <!-- Button untuk collapse detail -->
                                                <button class="btn btn-sm btn-secondary" type="button"
                                                    data-toggle="collapse" data-target="#detail-{{ $loop->iteration }}">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse multi-collapse" id="detail-{{ $loop->iteration }}"
                                            data-parent="#accordionPrediksi">
                                            <td colspan="5">
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
                                                <p>
                                                    <strong>Prediksi Rating =</strong>
                                                    {{ number_format($item->total_weighted, 4) }} ÷
                                                    {{ number_format($item->total_similarity, 4) }} =
                                                    {{ number_format($item->predicted_rating, 4) }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> {{-- Tutup accordion --}}
                    @else
                        <p class="text-center">Pilih pembeli untuk melihat rekomendasi</p>
                    @endif

                    @if (isset($similarUsers) && $similarUsers->count() > 0)
                        <h5 class="mt-4">Daftar Pembeli yang Mirip (Cosine Similarity)</h5>
                        <div style="max-height: 400px; overflow-y: auto;"> <!-- Fitur Scroll -->
                            <table class="table table-bordered">
                                <thead class="bg-danger text-white sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembeli</th>
                                        <th>Nama Pembeli</th>
                                        <th>Skor Cosine Similarity</th>
                                        <th class="text-center">Lihat Perhitungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($similarUsers as $sim)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sim->kode }}</td>
                                            <td>{{ $sim->nama }}</td>
                                            <td>{{ $sim->similarity }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('rekomendasi.show', ['id' => $sim->pembeli_id]) }}"
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
                </div>
            </div>
        </div>
    </div>
@endsection
