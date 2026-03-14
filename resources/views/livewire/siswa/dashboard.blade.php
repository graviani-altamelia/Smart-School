<div>
    {{-- Kode CSS --}}
    <style>
        .hero-card { background: linear-gradient(135deg, var(--sidebar-dark) 0%, #4a2d1f 100%); border-radius: 20px; }
        .btn-orange { background-color: var(--primary-orange) !important; color: white !important; border: none; transition: 0.3s; }
        .btn-orange:hover { background-color: #a0451a !important; transform: scale(1.02); }
        .btn-orange:disabled { background-color: #ccc !important; cursor: not-allowed; }
        .card-buku { border: none; transition: 0.3s; border-radius: 15px; }
        .card-buku:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .badge-status { font-size: 0.7rem; padding: 5px 12px; }
        .object-fit-cover { object-fit: cover; }
    </style>

    <div class="container">
        {{-- Header Statistik --}}
        <div class="card hero-card text-white border-0 p-4 p-lg-5 mb-4 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="fw-bold">Halo, {{ auth()->user()->name }}! 👋</h2>
                    <p class="opacity-75">Sudahkah kamu membaca buku hari ini?</p>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-3 justify-content-center justify-content-md-end mt-3 mt-md-0">
                        <div class="bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 text-center" style="min-width: 130px;">
                            <small class="d-block opacity-75">TOTAL BUKU</small>
                            {{-- Menampilkan jumlah total buku yang sedang dipinjam --}}
                            <span class="fs-4 fw-bold">{{ $pinjam_aktif }}</span>
                        </div>
                        <div class="bg-white bg-opacity-10 p-3 rounded-4 border border-white border-opacity-10 text-center" style="min-width: 130px;">
                            <small class="d-block opacity-75">DENDA</small>
                            <span class="fs-4 fw-bold text-warning">Rp{{ number_format($total_denda, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message')) 
            <div class="alert alert-success border-0 rounded-4 shadow-sm d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            </div> 
        @endif
        @if(session()->has('error')) 
            <div class="alert alert-danger border-0 rounded-4 shadow-sm d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div> 
        @endif

        <div class="row">
            {{-- Katalog Buku --}}
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Katalog Buku</h5>
                    <div class="col-6 col-md-5">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text border-0 bg-white ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" wire:model.live="search" class="form-control border-0 shadow-none px-2" placeholder="Cari judul buku...">
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($bukus as $buku)
                    <div class="col-6 col-md-4 col-xl-3">
                        <div class="card card-buku h-100 shadow-sm overflow-hidden">
                            <div class="bg-light d-flex align-items-center justify-content-center position-relative" style="height: 160px;">
                                @if($buku->cover)
                                    <img src="{{ asset('storage/'.$buku->cover) }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi bi-book text-muted opacity-25" style="font-size: 3rem;"></i>
                                @endif
                                
                                @if($buku->jumlah <= 0)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                                        <span class="badge bg-danger">Habis</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-3 text-center">
                                <h6 class="fw-bold text-truncate mb-1" title="{{ $buku->judul }}">{{ $buku->judul }}</h6>
                                <p class="text-muted small mb-3">Tersedia: <span class="fw-bold">{{ $buku->jumlah }}</span></p>
                                <button wire:click="pinjam({{ $buku->id }})" 
                                        wire:loading.attr="disabled"
                                        class="btn btn-orange btn-sm w-100 rounded-pill fw-bold"
                                        {{ $buku->jumlah <= 0 ? 'disabled' : '' }}>
                                    PINJAM
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Buku yang kamu cari tidak ditemukan.</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $bukus->links() }}</div>
            </div>

            {{-- Sidebar Aktivitas --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                <h5 class="fw-bold mb-4">Aktivitas Terbaru</h5>
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    @forelse($peminjaman_saya->take(5) as $pinjam)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom last-child-border-0">
                        <div class="flex-grow-1">
                            <div class="fw-bold small text-truncate" style="max-width: 180px;">{{ $pinjam->judul }}</div>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">
                                {{ $pinjam->jumlah_pinjam }} Buku • {{ \Carbon\Carbon::parse($pinjam->created_at)->diffForHumans() }}
                            </small>
                        </div>
                        <span class="badge 
                            {{ $pinjam->status_peminjaman == 'pending' ? 'bg-secondary-subtle text-secondary' : '' }}
                            {{ $pinjam->status_peminjaman == 'dipinjam' ? 'bg-info-subtle text-info' : '' }}
                            {{ $pinjam->status_peminjaman == 'dikembalikan' ? 'bg-success-subtle text-success' : '' }}
                            rounded-pill badge-status">
                            {{ strtoupper($pinjam->status_peminjaman) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">Belum ada aktivitas</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI --}}
    @if($showModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.6); z-index: 1050;" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold">Konfirmasi Peminjaman</h5>
                    <button wire:click="$set('showModal', false)" type="button" class="btn-close shadow-none"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h6 class="text-muted mb-1">Buku yang dipilih:</h6>
                        <h5 class="fw-bold text-primary">{{ $selectedBuku->judul }}</h5>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-uppercase mb-1">Tanggal Pinjam</label>
                        <input type="date" wire:model="tgl_pinjam" class="form-control bg-light border-0 rounded-3" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-uppercase mb-1">Tenggat Kembali</label>
                        <input type="date" wire:model="tgl_kembali" class="form-control border-0 bg-light rounded-3 shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold text-uppercase mb-1">Jumlah Buku (Maks: {{ $selectedBuku->jumlah }})</label>
                        <input type="number" wire:model="jumlah_pinjam" class="form-control border-0 bg-light rounded-3 shadow-sm" min="1" max="{{ $selectedBuku->jumlah }}">
                    </div>

                    <button wire:click="konfirmasiPinjam" 
                            wire:loading.attr="disabled"
                            class="btn btn-orange w-100 py-3 fw-bold rounded-3 shadow">
                        <span wire:loading.remove>KONFIRMASI SEKARANG</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-2"></span>Memproses...</span>
                    </button>
                    <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.75rem;">
                        *Stok akan otomatis berkurang setelah konfirmasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>