@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Produk</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="/produk/{{ $produk->id }}" method="post">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk"
                                class="form-control
                                @error('nama_produk') is-invalid @enderror"
                                value="{{ old('nama_produk', $produk->nama_produk) }}">
                            @error('nama_produk')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="kategori">Kategori</label>
                            <input type="text" name="kategori" id="kategori"
                                class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori', $produk->kategori) }}">
                            @error('kategori')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="harga">Harga</label>
                            <input type="text" name="harga" id="harga"
                                class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $produk->harga) }}">
                            @error('harga')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="stok">Stok</label>
                            <input type="text" name="stok" id="stok"
                                class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $produk->stok) }}">
                            @error('stok')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap:10px;">
                            <a href="/produk" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
