@extends('layouts.app')

@section('content')
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .sticky-top {
            top: 0;
            z-index: 10;
        }

        .btn-sm i {
            margin-right: 4px;
        }
    </style>

    <!-- Notifikasi berhasil dan error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 fade-in">
        <h1 class="h3 text-gray-800">Data Pembeli
        </h1>
        <div class="d-flex align-items-center">
            @if (Auth::user()->role_id == 1)
                <form id="form-import" action="{{ url('/pembeli/import') }}" method="POST" enctype="multipart/form-data"
                    class="mr-2 d-flex flex-column align-items-start">
                    @csrf
                    <div class="d-flex align-items-center">
                        <input type="file" name="file" class="form-control-file mr-2" accept=".csv,.xlsx" required>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                    <small class="text-muted mt-1">* Format file: .xlsx</small>
                    {{-- <div class="progress mt-2 w-100" style="height: 20px; display: none;" id="progress-container">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                            style="width: 10%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            Mengimpor data...
                        </div>
                    </div> --}}
                    <div id="progress-container" class="mt-2 w-100" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Mengimpor data...</span>
                        </div>
                        <span class="ml-2">Mengimpor data...</span>
                    </div>
                </form>
            @endif
            <a href="/pembeli/create" class="btn btn-sm btn-danger shadow-sm ml-3">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="row fade-in">
        <div class="col">
            <div class="card shadow fade-in">
                <div class="card-body">
                    <form action="{{ url('/pembeli') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama, atau lokasi..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    @if (request('search'))
                        <div class="alert alert-info fade-in">
                            Ditemukan {{ count($pembeli) }} data untuk pencarian: <strong>{{ request('search') }}</strong>
                        </div>
                    @endif

                    <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="bg-primary text-white sticky-top">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pembeli</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    @if (Auth::user()->role_id == 1)
                                        <th>Aksi</th>
                                    @endif
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
                                            <td>{{ $item->lokasi }}</td>
                                            @if (Auth::user()->role_id == 1)
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="/pembeli/{{ $item->id }}"
                                                            class="d-inline-block mr-2 btn btn-sm btn-warning">
                                                            <i class="fas fa-pen"></i> Edit
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#Delete-{{ $item->id }}">
                                                            <i class="fas fa-eraser"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
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

    <!-- Auto hide alert -->
    <script>
        setTimeout(() => {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    </script>

    <script>
        // Saat form import dikirim, tampilkan progress bar
        document.getElementById('form-import').addEventListener('submit', function() {
            document.getElementById('progress-container').style.display = 'block';
        });
    </script>

@endsection
