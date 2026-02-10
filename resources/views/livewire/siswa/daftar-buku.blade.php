<div>
    <div class="container py-4">
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1">📚 Katalog Buku</h2>
                <p class="text-muted">Temukan berbagai koleksi buku terbaik kami.</p>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-primary"></i></span>
                    <input wire:model.live="search" type="text" class="form-control border-0 px-2" placeholder="Cari judul buku atau penulis...">
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="row g-4">
            @forelse($bukus as $item)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 card-katalog">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="bg-primary bg-opacity-10 text-primary p-4 rounded-4 mb-3 text-center">
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $item->judul }}</h6>
                        <p class="small text-muted mb-3">Penulis: {{ $item->penulis }}</p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge {{ $item->jumlah > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3">
                                    Stok: {{ $item->jumlah }}
                                </span>
                            </div>
                            <button wire:click="pinjamBuku({{ $item->id }})" 
                                    class="btn btn-primary w-100 rounded-pill shadow-sm {{ $item->jumlah <= 0 ? 'disabled' : '' }}">
                                <i class="bi bi-plus-lg me-1"></i> Pinjam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted fs-4">Buku "{{ $search }}" tidak ditemukan.</div>
                <p>Coba kata kunci lain atau periksa ejaanmu.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $bukus->links() }}
        </div>
    </div>

    <style>
        .card-katalog { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05) !important; }
        .card-katalog:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
        .bg-success-subtle { background-color: #d1e7dd; }
        .bg-danger-subtle { background-color: #f8d7da; }
    </style>
</div>