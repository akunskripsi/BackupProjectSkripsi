<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Rating Prediksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekomendasis as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->kode_produk }}</td>
            <td>{{ $item->nama_produk }}</td>
            <td>{{ $item->harga }}</td>
            <td>{{ $item->rating_prediksi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
