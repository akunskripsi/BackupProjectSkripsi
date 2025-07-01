@extends('layouts.app')

@section('content')
    <style>
        .fade-in {
            animation: fadeIn 1s ease-in;
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

        .card:hover {
            transform: scale(1.03);
            transition: 0.3s;
            cursor: pointer;
        }
    </style>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 fade-in">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Jumlah Pembeli -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 fade-in"
                style="background: linear-gradient(to right, #4e73df, #224abe); color: white;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Pembeli</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $jumlahPembeli }}</div>
                            <div class="text-white-50 small">Total pengguna terdaftar</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-white rounded-circle p-2 shadow-sm">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Produk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 fade-in"
                style="background: linear-gradient(to right, #1cc88a, #159b66); color: white;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Produk</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $jumlahProduk }}</div>
                            <div class="text-white-50 small">Produk aktif tersedia</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-white rounded-circle p-2 shadow-sm">
                                <i class="fas fa-box fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Rating -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 fade-in"
                style="background: linear-gradient(to right, #36b9cc, #278a9e); color: white;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Rating</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $jumlahRating }}</div>
                            <div class="text-white-50 small">Penilaian yang diberikan</div>
                        </div>
                        <div class="col-auto">
                            <div class="bg-white rounded-circle p-2 shadow-sm">
                                <i class="fas fa-star fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
