@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Detail Perhitungan Cosine Similarity</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Produk</th>
                    <th>Rating Pembeli Terpilih</th>
                    <th>Rating Pembeli Lain</th>
                    <th>a × b</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $item['produk_id'] }}</td>
                        <td>{{ $item['rating_A'] }}</td>
                        <td>{{ $item['rating_B'] }}</td>
                        <td>{{ $item['a_x_b'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Dot Product:</strong> {{ $dotProduct }}</p>
        <p><strong>Magnitude A:</strong> {{ $magnitudeA }}</p>
        <p><strong>Magnitude B:</strong> {{ $magnitudeB }}</p>
        <p><strong>Cosine Similarity:</strong> {{ round($similarity, 4) }}</p>

        <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
