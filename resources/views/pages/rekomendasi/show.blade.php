@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Detail Perhitungan Cosine Similarity</h3>

        <style>
            /* Table nowrap */
            table th,
            table td {
                white-space: nowrap;
            }

            /* Animasi fade-in dan slide down */
            .fade-in-slide {
                opacity: 0;
                transform: translateY(-20px);
                animation: fadeSlideDown 0.8s ease forwards;
            }

            @keyframes fadeSlideDown {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        <!-- Tabel dengan animasi -->
        <table class="table table-bordered fade-in-slide">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Rating Pembeli Terpilih</th>
                    <th>Rating Pembeli Lain</th>
                    <th>a × b</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['produk_id'] }}</td>
                        <td>{{ $item['nama_produk'] }}</td>
                        <td>{{ $item['rating_A'] }}</td>
                        <td>{{ $item['rating_B'] }}</td>
                        <td>{{ $item['a_x_b'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $magnitudeA_sq = 0;
            $magnitudeB_sq = 0;
            $dotProductFormula = [];
        @endphp

        @foreach ($details as $item)
            @php
                $magnitudeA_sq += pow($item['rating_A'], 2);
                $magnitudeB_sq += pow($item['rating_B'], 2);
                $dotProductFormula[] = "({$item['rating_A']}×{$item['rating_B']})";
            @endphp
        @endforeach

        <p><strong>Dot Product:</strong> {{ implode(' + ', $dotProductFormula) }} = {{ $dotProduct }}</p>
        <p><strong>Magnitude A:</strong>
            √({{ implode(' + ', collect($details)->map(fn($d) => "({$d['rating_A']}²)")->toArray()) }}) =
            {{ round($magnitudeA, 4) }}</p>
        <p><strong>Magnitude B:</strong>
            √({{ implode(' + ', collect($details)->map(fn($d) => "({$d['rating_B']}²)")->toArray()) }}) =
            {{ round($magnitudeB, 4) }}</p>
        <p><strong>Cosine Similarity:</strong> {{ $dotProduct }} / ({{ round($magnitudeA, 4) }} ×
            {{ round($magnitudeB, 4) }}) = {{ round($similarity, 4) }}</p>

            <a href="{{ route('rekomendasi.index', [
    'pembeli_id' => $selectedUserId,
    'kategori' => $kategori
]) }}" class="btn btn-secondary mt-3">
    Kembali
</a>
             
    </div>
@endsection
