<div> 

    <div class="container py-4">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="p-5 mb-4 bg-primary text-white rounded-4 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-start">
                    <h1 class="display-5 fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="fs-5">
                        Kamu memiliki <strong>{{ $jumlah_pinjam_aktif }}</strong> buku yang sedang dipinjam.
                    </p>
                    <a href="{{ route('siswa.riwayat-pinjam') }}" wire:navigate class="btn btn-light btn-lg rounded-pill px-4 text-primary fw-bold mt-2">
                        <i class="bi bi-clock-history me-1"></i> Cek Riwayat
                    </a>
                </div>
                <div class="col-md-4 d-none d-md-block text-center">
                    <img src="https://illustrations.popsy.co/white/reading-a-book.svg" style="height: 200px;" alt="Banner">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">📚 Buku Terbaru</h4>
            <a href="{{ route('siswa.daftar-buku') }}" wire:navigate class="text-decoration-none fw-bold">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($buku as $item)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 mb-3 text-center">
                                <i class="bi bi-book fs-1"></i>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $item->judul }}</h6>
                            <p class="small text-muted mb-3 italic">Oleh: {{ $item->penulis }}</p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                    Stok: {{ $item->jumlah }}
                                </span>
                                <button wire:click="pinjamBuku({{ $item->id }})" 
                                        class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm {{ $item->jumlah <= 0 ? 'disabled' : '' }}">
                                    Pinjam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada koleksi buku baru.</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-7px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .bg-primary-subtle { background-color: #e7f0ff; }
    </style>

</div> 