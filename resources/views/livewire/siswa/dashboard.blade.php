<div>
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Katalog Buku Digital</h3>
                <p class="text-muted mb-0">Silahkan cari dan pilih buku yang ingin dipinjam.</p>
            </div>
            <div style="min-width: 300px;">
                <div class="input-group shadow-sm rounded-3 overflow-hidden border">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                    <input wire:model.live="search" type="text" class="form-control border-0 ps-0" placeholder="Cari judul atau penulis...">
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle me-2"></i> {{ session('message') }}
            </div>
        @endif

        <div class="row g-4">
            @forelse($bukus as $item)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 card-book">
                        <div class="p-4 text-center bg-light border-bottom">
                            <i class="bi bi-journal-bookmark text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>
                            <p class="text-muted small mb-3">Oleh: {{ $item->penulis }}</p>
                            
                            <div class="mt-auto">
                                <span class="badge rounded-pill {{ $item->jumlah > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} mb-3">
                                    Stok: {{ $item->jumlah }}
                                </span>
                                <button wire:click="pilihBuku({{ $item->id }})" 
                                        class="btn btn-primary w-100 rounded-pill shadow-sm {{ $item->jumlah <= 0 ? 'disabled' : '' }}">
                                    Pinjam Buku
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Buku tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $bukus->links() }}</div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($selected_buku)
                        <div class="text-center mb-4">
                            <i class="bi bi-info-circle text-primary" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 fw-bold">{{ $selected_buku->judul }}</h5>
                            <p class="text-muted">Penulis: {{ $selected_buku->penulis }}</p>
                        </div>
                        <div class="bg-light p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tanggal Pinjam:</span>
                                <span class="fw-bold">{{ now()->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Batas Kembali:</span>
                                <span class="fw-bold text-danger">{{ now()->addDays(7)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <p class="small text-center text-muted">Pastikan kamu mengembalikan buku tepat waktu untuk menghindari denda.</p>
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button wire:click="pinjamBuku" class="btn btn-primary rounded-pill px-4 shadow-sm">Konfirmasi Pinjam</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('show-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('modalPinjam'));
            myModal.show();
        });
        window.addEventListener('hide-modal', event => {
            var myModalEl = document.getElementById('modalPinjam');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        });
    </script>
</div>