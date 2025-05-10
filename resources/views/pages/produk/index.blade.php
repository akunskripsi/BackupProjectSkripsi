@extends('layouts.app')

@section('content')
<!-- notifikasi berhasil dan eror -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Produk</h1>
        <a href="/produk/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>

    {{-- Tables --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ url('/produk') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk, kategori..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Notifikasi hasil pencarian --}}
                    @if (request('search'))
                        <div class="alert alert-info">
                            Ditemukan {{ count($produk) }} data untuk pencarian: <strong>{{ request('search') }}</strong>
                        </div>
                    @endif

                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @if (count($produk) < 1)
                                <tbody>
                                    <tr>
                                        <td colspan="7">
                                            <p class="pt-3 text-center">Tidak Ada Data</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @else
                                <tbody>
                                    @foreach ($produk as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->nama_produk }}</td>
                                            <td>{{ $item->kategori }}</td>
                                            <td>{{ $item->harga }}</td>
                                            <td>{{ $item->stok }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="/produk/{{ $item->id }}"
                                                        class="d-inline-block mr-2
                                        btn btn-sm btn-warning">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#Delete-{{ $item->id }}">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('pages.produk.delete')
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
