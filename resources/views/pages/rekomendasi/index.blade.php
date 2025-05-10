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

                    @if (count($rekomendasis) > 0)
                        @if (isset($notifikasi))
                            <div class="alert alert-warning">
                                {{ $notifikasi }}
                            </div>
                        @endif

                        <h5 class="mt-4">Daftar Hasil Rekomendasi</h5>
                        <table class="table table-bordered table-hovered">
                            <thead class="bg-warning text-white sticky-top">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Produk</th>
                                    <th>Rating Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekomendasis as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->predicted_rating }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center">Pilih pembeli untuk melihat rekomendasi</p>
                    @endif

                    @if ($similarUsers->count() > 0)
                        <h5 class="mt-4">Daftar Pembeli yang Mirip (Cosine Similarity)</h5>
                        <div style="max-height: 400px; overflow-y: auto;"> <!-- Fitur Scroll -->
                            <table class="table table-bordered">
                                <thead class="bg-warning text-white sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pembeli</th>
                                        <th>Nama Pembeli</th>
                                        <th>Skor Cosine Similarity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($similarUsers as $sim)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sim->kode }}</td>
                                            <td>{{ $sim->nama }}</td>
                                            <td>{{ $sim->similarity }}</td>
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
