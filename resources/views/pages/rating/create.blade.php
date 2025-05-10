@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Rating</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="/rating" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="pembeli_id">Nama Pembeli</label>
                            <select name="pembeli_id" id="pembeli_id"
                                class="form-control @error('pembeli_id') is-invalid @enderror">
                                <option value="">-- Pilih Pembeli --</option>
                                @foreach ($pembelis as $pembeli)
                                    <option value="{{ $pembeli->id }}"
                                        {{ old('pembeli_id') == $pembeli->id ? 'selected' : '' }}>
                                        {{ $pembeli->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pembeli_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="produk_id">Nama Produk</label>
                            <select name="produk_id" id="produk_id"
                                class="form-control @error('produk_id') is-invalid @enderror">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}"
                                        {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="stok">Rating</label>
                            <input type="number" name="rating" id="rating"
                                class="form-control @error('rating') is-invalid @enderror" value="{{ old('rating') }}"
                                min="1" max="5" placeholder="Masukkan Rating 1–5">
                            @error('rating')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap:10px;">
                            <a href="/rating" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-danger">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
