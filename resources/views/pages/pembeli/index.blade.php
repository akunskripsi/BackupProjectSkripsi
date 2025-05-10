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
        <h1 class="h3 mb-0 text-gray-800">Data Pembeli</h1>
        <a href="/pembeli/create" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Tambah</a>
    </div>

    {{-- Tables --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ url('/pembeli') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama, email, atau lokasi..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    @if (request('search'))
                        <div class="alert alert-info">
                            Ditemukan {{ count($pembeli) }} data untuk pencarian: <strong>{{ request('search') }}</strong>
                        </div>
                    @endif


                    <div class="table-responsive" style="max-height: 900px; overflow-y: auto;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-warning text-white sticky-top">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pembeli</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @if (count($pembeli) < 1)
                                <tbody>
                                    <tr>
                                        <td colspan="6">
                                            <p class="pt-3 text-center">Tidak Ada Data</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @else
                                <tbody>
                                    @foreach ($pembeli as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_pembeli }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->lokasi }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="/pembeli/{{ $item->id }}"
                                                        class="d-inline-block mr-2 btn btn-sm btn-warning">
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
                                        @include('pages.pembeli.delete')
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
