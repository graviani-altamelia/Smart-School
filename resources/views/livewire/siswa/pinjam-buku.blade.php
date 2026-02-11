<div>
    <div class="container py-4">
        {{-- 1. HERO SECTION DENGAN KOTAK STATISTIK KONTRAS TINGGI --}}
        <div class="col-md-12 mb-5">
            <div class="card border-0 shadow-sm rounded-4 text-white overflow-hidden" 
                 style="background: linear-gradient(135deg, #0d6efd 0%, #007bff 100%);">
                <div class="card-body p-4 p-lg-5 position-relative">
                    <div class="row align-items-center" style="z-index: 2; position: relative;">
                        <div class="col-md-7">
                            <h1 class="fw-bold mb-2">Hai, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                            <p class="fs-5 opacity-90 mb-4">Mau tambah wawasan apa hari ini? Cari bukunya di bawah ya!</p>
                            
                            <div class="d-flex gap-3">
                                {{-- Kotak Pinjam --}}
                                <div class="bg-white p-3 rounded-4 shadow-sm" style="min-width: 160px;">
                                    <small class="d-block text-uppercase fw-bold mb-1" style="color: #6c757d; font-size: 0.7rem; letter-spacing: 1px;">Pinjaman Aktif</small>
                                    <div class="d-flex align-items-center">
                                        <span class="fs-4 fw-bold text-primary me-2">{{ $stat_pinjam ?? 0 }}</span>
                                        <span class="fs-4 fw-bold text-primary">Buku</span>
                                    </div>
                                </div>
                                {{-- Kotak Denda - SEKARANG PUTIH PEKAT --}}
                                <div class="bg-white p-3 rounded-4 shadow-sm" style="min-width: 150px;">
                                    <small class="d-block text-uppercase fw-bold mb-1" style="color: #6c757d; font-size: 0.7rem; letter-spacing: 1px;">Total Denda</small>
                                    <div class="d-flex align-items-baseline">
                                        <span class="fs-4 fw-bold text-danger">Rp{{ number_format($stat_denda ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Ikon Dekoratif --}}
                    <i class="bi bi-book position-absolute end-0 top-50 translate-middle-y opacity-10 d-none d-md-block" style="font-size: 160px; margin-right: 40px; pointer-events: none;"></i>
                </div>
            </div>
        </div>

        {{-- 2. NOTIFIKASI --}}
        @if (session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeIn">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 animate__animated animate__shakeX">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        {{-- 3. MODAL KONFIRMASI PEMINJAMAN --}}
        @if($buku_id)
            <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                 style="background: rgba(15, 23, 42, 0.8); z-index: 9999; backdrop-filter: blur(8px);">
                <div class="card border-0 shadow-lg rounded-4 animate__animated animate__zoomIn" style="width: 90%; max-width: 450px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 text-center">
                        <h5 class="fw-bold mb-0 text-dark">Konfirmasi Peminjaman</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if($bukuTerpilih)
                            <div class="mb-4">
                                <i class="bi bi-book-half display-4 text-primary mb-2"></i>
                                <h6 class="fw-bold mb-0 px-3">{{ $bukuTerpilih->judul }}</h6>
                                <small class="text-muted">Karya: {{ $bukuTerpilih->penulis }}</small>
                            </div>
                        @endif
                        
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <small class="text-muted d-block small">Tgl Pinjam</small>
                                    <span class="fw-bold small">{{ date('d M Y', strtotime($tgl_pinjam)) }}</span>
                                </div>
                            </div>
                            <div class="col-6 text-start">
                                <label class="small fw-bold text-muted ms-1">Tgl Kembali</label>
                                <input type="date" class="form-control form-control-sm rounded-3 border-primary shadow-sm" wire:model="tgl_kembali">
                                @error('tgl_kembali') <small class="text-danger d-block mt-1" style="font-size: 10px;">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="pinjam" wire:loading.attr="disabled" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                                <span wire:loading.remove>PINJAM BUKU SEKARANG</span>
                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                            </button>
                            <button wire:click="batal" class="btn btn-link text-muted text-decoration-none small">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 4. SEARCH & KATALOG --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h4 class="fw-bold text-dark mb-0">Koleksi Buku Terbaru 📚</h4>
            <div class="col-md-5">
                <div class="input-group rounded-pill border bg-white px-3 shadow-sm">
                    <span class="input-group-text bg-transparent border-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" wire:model.live="search" class="form-control bg-transparent border-0 shadow-none py-2" placeholder="Cari judul buku atau penulis...">
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($bukus as $item)
                <div class="col-6 col-md-4 col-lg-3" wire:key="buku-{{ $item->id }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4 card-hover overflow-hidden bg-white">
                        <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 220px;">
                            @if($item->cover)
                                <img src="{{ asset('storage/'.$item->cover) }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <i class="bi bi-journal-text text-primary opacity-20" style="font-size: 3.5rem;"></i>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($item->jumlah > 0)
                                    <span class="badge bg-success rounded-pill px-3 shadow-sm">Tersedia</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 shadow-sm">Habis</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold text-dark text-truncate mb-1">{{ $item->judul }}</h6>
                            <p class="text-muted small mb-3">Penulis: {{ $item->penulis ?? '-' }}</p>
                            
                            <button type="button" 
                                    wire:click="tampilkanForm({{ $item->id }})" 
                                    class="btn {{ $item->jumlah > 0 ? 'btn-primary' : 'btn-light disabled text-muted' }} mt-auto rounded-pill py-2 small fw-bold shadow-sm">
                                {{ $item->jumlah > 0 ? 'PINJAM' : 'HABIS' }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Buku tidak tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $bukus->links() }}
        </div>
    </div>

    <style>
        .fw-black { font-weight: 900; }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important; 
        }
    </style>
</div>