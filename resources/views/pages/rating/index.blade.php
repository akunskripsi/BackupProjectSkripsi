@extends('layouts.app')

@section('content')
    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-hover tbody tr:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .sticky-top {
            top: 0;
            z-index: 100;
        }
    </style>

    {{-- Alert Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 fade-in">
        <h1 class="h3 text-gray-800">
            Data Rating
        </h1>
        <div class="d-flex align-items-center">
            @if (Auth::user()->role_id == 1)
                <form id="form-import" action="{{ url('/rating/import') }}" method="POST" enctype="multipart/form-data"
                    class="mr-2 d-flex flex-column align-items-start">
                    @csrf
                    <div class="d-flex align-items-center">
                        <input type="file" name="file" class="form-control-file mr-2" accept=".csv,.xlsx" required>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                    <small class="text-muted mt-1">* Format file: .xlsx</small>
                    <div id="progress-container" class="mt-2 w-100" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Mengimpor data...</span>
                        </div>
                        <span class="ml-2">Mengimpor data...</span>
                    </div>
                </form>
            @endif
            @if (Auth::user()->role_id == 2)
                <a href="/rating/create" class="btn btn-sm btn-danger shadow-sm ml-2">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            @endif
        </div>
    </div>

    <!-- Tabel Rating -->
    <div class="row fade-in">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ url('/rating') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Pembeli..."
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    @if (request('search'))
                        <div class="alert alert-info fade-in">
                            Ditemukan {{ count($pembelis) }} pembeli untuk pencarian:
                            <strong>{{ request('search') }}</strong>
                        </div>
                    @endif

                    <div class="table-responsive" style="max-height: 700px; overflow-y:auto;">
                        <table class="table table-bordered table-hover table-striped text-center align-middle text-nowrap">
                            <thead class="bg-primary text-white sticky-top">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pembeli</th>
                                    @foreach ($produks as $produk_nama)
                                        <th>{{ $produk_nama }}</th>
                                    @endforeach
                                    @if (Auth::user()->role_id == 2)
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="fw-bold">
                                @forelse ($pembelis as $pembeli_id => $nama_pembeli)
                                    <tr>
                                        <td>{{ $loop->iteration + ($pembelis->currentPage() - 1) * $pembelis->perPage() }}
                                        </td>
                                        <td>{{ $nama_pembeli }}</td>
                                        @foreach ($produks as $produk_id => $produk_nama)
                                            <td>{{ $ratingMatrix[$pembeli_id][$produk_id] ?? '-' }}</td>
                                        @endforeach
                                        @if (Auth::user()->role_id == 2)
                                            <td>
                                                <button class="btn btn-sm btn-warning mr-1" data-bs-toggle="modal"
                                                    data-bs-target="#editModal" data-id="{{ $pembeli_id }}"
                                                    data-nama="{{ $nama_pembeli }}"
                                                    data-rating='@json($ratingMatrix[$pembeli_id] ?? [])'>
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <form action="{{ url('/rating/' . $pembeli_id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 + count($produks) }}" class="text-left">Tidak ada data rating
                                            ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $pembelis->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pembeli dan Rating</h5>
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pembeli_id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nama">Nama Pembeli</label>
                            <input type="text" class="form-control" id="edit-nama" name="nama">
                        </div>
                        <div class="mb-3">
                            <label>Rating Produk</label>
                            <div class="row">
                                @foreach ($produks as $produk_id => $produk_nama)
                                    <div class="col-md-4 mb-2">
                                        <label>{{ $produk_nama }}</label>
                                        <input type="number" min="1" max="5"
                                            class="form-control rating-input" name="ratings[{{ $produk_id }}]"
                                            data-produk-id="{{ $produk_id }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Modal Logic -->
    <script>
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const ratings = JSON.parse(button.getAttribute('data-rating') || '{}');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;

            document.querySelectorAll('.rating-input').forEach(input => {
                const produkId = input.dataset.produkId;
                input.value = ratings[produkId] ?? '';
            });

            document.getElementById('formEdit').action = `/rating/${id}`;
        });

        // Auto-dismiss alert
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => el.classList.remove('show'));
        }, 4000);
    </script>

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
