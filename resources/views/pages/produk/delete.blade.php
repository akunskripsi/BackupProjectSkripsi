<!-- Modal -->
<div class="modal fade" id="Delete-{{ $item->id }}" tabindex="-1" aria-labelledby="DeleteLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="/produk/{{ $item->id }}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="DeleteLabel">Konfirmasi Hapus</h4>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <span>Apakah anda yakin akan menghapus data?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-outline-danger">Ya, Hapus!</button>
                </div>
            </div>
        </form>
    </div>
</div>
