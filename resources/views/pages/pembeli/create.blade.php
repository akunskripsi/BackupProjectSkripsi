@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Pembeli</h1>
    </div>

    <div class="row">
        <div class="col">
            <form action="/pembeli" method="post">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="kode_pembeli">Kode Pembeli</label>
                            <input type="text" name="kode_pembeli" id="kode_pembeli"
                                class="form-control @error('kode_pembeli') is-invalid @enderror"
                                value="{{ old('kode_pembeli', $newKodePembeli) }}" readonly>
                            @error('kode_pembeli')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" name="name" id="name"
                                class="form-control
                                @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="lokasi">Lokasi</label>
                            <input type="text" name="lokasi" id="lokasi"
                                class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}">
                            @error('lokasi')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end" style="gap:10px;">
                            <a href="/pembeli" class="btn btn-outline-secondary">
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
