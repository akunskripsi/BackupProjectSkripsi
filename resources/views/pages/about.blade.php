@extends('layouts.app') <!-- Sesuaikan dengan layout utama kamu -->

@section('content')
    <div class="container mt-5">
        <div class="card shadow rounded-3">
            <div class="card-body">
                <h2 class="text-center mb-4">Tentang Kami</h2>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('template/img/logo-login.jpg') }}" alt="Tim Kami"
                            class="img-fluid rounded-circle mb-3" width="200">
                        <h5>Tim Pengembang</h5>
                    </div>
                    <div class="col-md-8" style="text-align: justify;">
                        <p>
                            Toko Surya Elektrik adalah penyedia peralatan elektronik dan kabel berkualitas di Malang.
                            Kami mengutamakan kepuasan pelanggan melalui produk original dan layanan ramah.
                        </p>
                        <p>
                            Sistem Rekomendasi Produk ini dikembangkan sebagai bagian dari proyek penelitian yang bertujuan
                            untuk meningkatkan pengalaman belanja pengguna
                            dengan memberikan rekomendasi berdasarkan preferensi pembeli lain yang memiliki kesamaan pola
                            perilaku.
                        </p>
                        <p>
                            Metode yang digunakan adalah <strong>Collaborative Filtering</strong>, yang memungkinkan sistem
                            untuk mempelajari hubungan antar pembeli
                            dan memberikan saran produk yang lebih relevan.
                        </p>
                        <p>
                            Proyek ini dirancang menggunakan framework <strong>Laravel</strong>, dengan dukungan Bootstrap
                            untuk tampilan antarmuka yang responsif
                            dan modern.
                        </p>
                    </div>
                    <div class="mt-4 p-3 bg-light rounded shadow-sm">
                        <h5 class="mb-3"><strong>Hubungi Kami</strong></h5>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                            JL. Gudang Raya RT 3 RW 11, Gedog Wetan, Turen, Kabupaten Malang
                        </p>
                        <p>
                            <i class="fas fa-envelope text-danger mr-2"></i>
                            <a href="mailto:tokosuryaelektrik@gmail.com" class="text-decoration-none">
                                tokosuryaelektrik@gmail.com
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
