<div class="container py-4">
    <div class="row g-4">
        {{-- Hero Section: Sapaan Personal --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden" 
                 style="background: linear-gradient(45deg, #0d6efd, #0099ff);">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center" style="z-index: 2; position: relative;">
                        <div class="col-md-8">
                            <h1 class="fw-bold mb-2">Hai, {{ auth()->user()->name }}! 👋</h1>
                            <p class="fs-5 opacity-75 mb-4">Ayo jelajahi dunia melalui buku. Mau baca apa kita hari ini?</p>
                            
                            <div class="d-flex gap-3">
                                <div class="bg-white bg-opacity-20 p-3 rounded-4 backdrop-blur">
                                    <small class="d-block opacity-75">Buku Dipinjam</small>
                                    <span class="fs-4 fw-bold">{{ $jumlah_pinjam_aktif ?? 0 }}</span>
                                </div>
                                <div class="bg-white bg-opacity-20 p-3 rounded-4 backdrop-blur">
                                    <small class="d-block opacity-75">Tanggungan Denda</small>
                                    <span class="fs-4 fw-bold">Rp {{ number_format($total_denda ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Dekorasi Ikon Melayang --}}
                    <i class="bi bi-book position-absolute end-0 top-50 translate-middle-y opacity-10 d-none d-md-block" 
                       style="font-size: 200px; margin-right: 20px;"></i>
                </div>
            </div>
        </div>

        {{-- Section: Rekomendasi / Katalog --}}
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Katalog Buku Terbaru 📚</h4>
                <div class="col-md-4">
                    <div class="input-group rounded-pill border-0 shadow-sm bg-white px-3">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live="search" class="form-control bg-transparent border-0 shadow-none py-2" placeholder="Cari judul buku atau penulis...">
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($bukus as $buku)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover border-hover-primary">
                        <div class="position-relative">
                            @if($buku->cover)
                                <img src="{{ asset('storage/'.$buku->cover) }}" class="card-img-top rounded-t-4" alt="{{ $buku->judul }}" style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-top-4" style="height: 250px;">
                                    <i class="bi bi-book text-muted display-4"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-2 badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <small class="text-primary fw-bold text-uppercase" style="font-size: 10px;">{{ $buku->kategori->nama ?? 'Umum' }}</small>
                            <h6 class="fw-bold text-dark text-truncate mb-1 mt-1" title="{{ $buku->judul }}">{{ $buku->judul }}</h6>
                            <p class="text-muted small mb-3">Penulis: {{ $buku->penulis }}</p>
                            
                            <button wire:click="pinjam({{ $buku->id }})" 
                                    class="btn {{ $buku->stok > 0 ? 'btn-primary' : 'btn-secondary disabled' }} w-100 rounded-pill py-2 shadow-sm fw-bold"
                                    @if($buku->stok <= 0) disabled @endif>
                                <i class="bi bi-plus-circle me-1"></i> Pinjam Buku
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-muted opacity-25"></i>
                    <p class="text-muted mt-3">Buku yang kamu cari tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-5 d-flex justify-content-center">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .transition-hover:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .border-hover-primary:hover { border: 1px solid #0d6efd !important; }
    .backdrop-blur { backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
</style>