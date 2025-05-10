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

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Rating</h1>
        <a href="/rating/create" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
        </a>
    </div>

    <div class="row">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-nowrap">
                            <thead class="bg-warning text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pembeli</th>
                                    @foreach ($produks as $produk_nama)
                                        <th>{{ $produk_nama }}</th>
                                    @endforeach
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center fw-bold" style="color: #000; background-color: #fff;">
                                @forelse ($pembelis as $pembeli_id => $nama_pembeli)
                                    <tr>
                                        <td>{{ $loop->iteration + ($pembelis->currentPage() - 1) * $pembelis->perPage() }}
                                        </td>
                                        <td>{{ $nama_pembeli }}</td>
                                        @foreach ($produks as $produk_id => $produk_nama)
                                            <td>
                                                {{ $ratingMatrix[$pembeli_id][$produk_id] ?? '-' }}
                                            </td>
                                        @endforeach
                                        <td>
                                            <button class="d-inline-block mr-2 btn btn-sm btn-warning"
                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-id="{{ $pembeli_id }}" data-nama="{{ $nama_pembeli }}"
                                                data-rating='@json($ratingMatrix[$pembeli_id] ?? [])'>
                                                <i class="fas fa-pen"></i></button>

                                            <form action="{{ url('/rating/' . $pembeli_id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fas fa-eraser"></i></button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 2 + count($produks) }}" class="text-start">
                                            Tidak ada data rating ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $pembelis->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Pembeli + Rating -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pembeli dan Rating</h5>
                        <button type="button" class="btn btn-sm btn-light rounded-circle" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pembeli_id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Nama Pembeli</label>
                            <input type="text" class="form-control" id="edit-nama" name="nama">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rating Produk</label>
                            <div class="row">
                                @foreach ($produks as $produk_id => $produk_nama)
                                    <div class="col-md-4">
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


    <script>
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const ratings = JSON.parse(button.getAttribute('data-rating') || '{}');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;

            // Isi nilai rating tiap input
            document.querySelectorAll('.rating-input').forEach(input => {
                const produkId = input.dataset.produkId;
                input.value = ratings[produkId] ?? '';
            });

            // Atur URL action
            document.getElementById('formEdit').action = `/rating/${id}`;
        });
    </script>
@endsection
