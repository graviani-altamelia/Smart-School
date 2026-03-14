<div>
    <style>
        .modal-custom {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(28, 15, 10, 0.85); backdrop-filter: blur(8px);
            z-index: 1060; display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .btn-earth { 
            background: linear-gradient(135deg, #d67d3e 0%, #b05a28 100%); 
            color: white; border: none; transition: 0.4s; 
            box-shadow: 0 4px 15px rgba(214, 125, 62, 0.3);
        }
        .btn-earth:hover { transform: translateY(-2px); color: white; opacity: 0.9; }
        .card-katalog { border-radius: 24px; transition: 0.4s; background: #fff; border: none; }
        .card-katalog:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .book-icon-wrapper { background: #f8f9fa; border-radius: 18px; height: 180px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; position: relative; }
        .stok-badge { position: absolute; top: 10px; right: 10px; padding: 5px 12px; border-radius: 12px; font-size: 0.7rem; font-weight: bold; background: rgba(255,255,255,0.9); color: #444; }
        .form-control-custom { border: 1.5px solid #eee; border-radius: 15px; padding: 12px 15px; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="container py-5">
        <div class="row mb-5 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2 class="fw-bold mb-1">Katalog Koleksi</h2>
                <p class="text-muted">Temukan buku favoritmu di sini.</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="input-group shadow-sm rounded-pill bg-white p-1">
                    <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-search"></i></span>
                    <input wire:model.live="search" type="text" class="form-control border-0 rounded-pill shadow-none" placeholder="Cari buku...">
                </div>
            </div>
        </div>

        @if (session()->has('message')) <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">{{ session('message') }}</div> @endif
        @if (session()->has('error')) <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">{{ session('error') }}</div> @endif

        <div class="row g-4">
            @foreach($bukus as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-katalog h-100 shadow-sm p-3">
                    <div class="book-icon-wrapper">
                        <span class="stok-badge">Stok: {{ $item->jumlah }}</span>
                        <i class="bi bi-book-half fs-1 text-muted opacity-25"></i>
                    </div>
                    <h6 class="fw-bold text-dark text-truncate mb-1">{{ $item->judul }}</h6>
                    <p class="small text-muted mb-3">{{ $item->penulis ?? 'Anonim' }}</p>
                    <button wire:click="pinjam({{ $item->id }})" class="btn btn-earth w-100 rounded-pill py-2 fw-bold {{ $item->jumlah <= 0 ? 'disabled' : '' }}">
                        {{ $item->jumlah <= 0 ? 'HABIS' : 'PINJAM' }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $bukus->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    @if($showModal && $selectedBuku)
    <div class="modal-custom">
        <div class="card border-0 shadow-lg rounded-5 overflow-hidden" style="width: 100%; max-width: 480px; animation: slideUp 0.3s ease;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Detail Peminjaman</h5>
                <button wire:click="$set('showModal', false)" class="btn-close"></button>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="p-3 rounded-4 mb-4" style="background: #fff8f4; border: 1px dashed #d67d3e;">
                    <p class="small text-muted mb-0">Buku:</p>
                    <h6 class="fw-bold mb-0">{{ $selectedBuku->judul }}</h6>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small fw-bold mb-2">Tanggal Pinjam</label>
                        <input type="date" wire:model="tgl_pinjam" class="form-control form-control-custom bg-light" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-2">Tanggal Kembali</label>
                        <input type="date" wire:model="tgl_kembali" class="form-control form-control-custom">
                    </div>
                    <div class="col-12">
                        <label class="small fw-bold mb-2">Jumlah Buku (Maks: {{ $selectedBuku->jumlah }})</label>
                        <input type="number" wire:model="jumlah_pinjam" class="form-control form-control-custom" min="1" max="{{ $selectedBuku->jumlah }}">
                        @error('jumlah_pinjam') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button wire:click="pinjamBuku" wire:loading.attr="disabled" class="btn btn-earth w-100 rounded-pill py-3 fw-bold shadow">
                        <span wire:loading.remove>KONFIRMASI PINJAM</span>
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>