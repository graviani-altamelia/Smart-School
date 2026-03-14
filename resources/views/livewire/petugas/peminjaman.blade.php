<div>
    {{-- Header & Search Bar --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Peminjaman Aktif</h3>
            <p class="text-muted small mb-0">Kelola validasi dan pengembalian buku dalam satu layar.</p>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2 justify-content-md-end">
                {{-- SEARCH BAR OVAL --}}
                <div class="input-group shadow-sm search-oval w-75">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input wire:model.live="search" type="text" class="form-control border-0 px-2 shadow-none" placeholder="Cari nama atau judul...">
                </div>
                
                {{-- TOMBOL TAMBAH ORANGE --}}
                <button wire:click="create" class="btn btn-orange shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-plus-lg fs-5"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session()->has('message'))
        <div class="alert alert-custom border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 text-orange"></i> 
            <span class="text-dark fw-medium">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Tabel Utama --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-sidebar text-white">
                    <tr class="small fw-bold">
                        <th class="ps-4 py-3 border-0">SISWA</th>
                        <th class="border-0">INFORMASI BUKU</th>
                        <th class="text-center border-0">TENGGAT</th>
                        <th class="text-center border-0">STATUS</th>
                        <th class="text-center pe-4 border-0">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $p->user->name }}</div>
                            <div class="text-muted small">NIS: {{ $p->user->nis ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="text-dark fw-medium text-truncate" style="max-width: 200px;">{{ $p->judul }}</div>
                            <div class="text-muted small">Qty: {{ $p->jumlah_pinjam }}</div>
                        </td>
                        <td class="text-center small">
                            <span class="{{ \Carbon\Carbon::parse($p->tgl_kembali)->isPast() && $p->status_peminjaman == 'dipinjam' ? 'badge bg-danger-subtle text-danger rounded-pill' : 'text-dark' }} px-2 py-1">
                                {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($p->status_peminjaman == 'pending')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">Menunggu</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Dipinjam</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden border">
                                @if($p->status_peminjaman == 'pending')
                                    <button wire:click="setujui({{ $p->id }})" class="btn btn-white btn-sm border-0" title="Setujui">
                                        <i class="bi bi-check-lg text-success fw-bold"></i>
                                    </button>
                                    <button wire:click="tolak({{ $p->id }})" class="btn btn-white btn-sm border-0" title="Tolak">
                                        <i class="bi bi-x-lg text-danger fw-bold"></i>
                                    </button>
                                @else
                                    <button wire:click="kembalikan({{ $p->id }})" class="btn btn-white btn-sm border-0 text-primary" title="Proses Kembali">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                @endif
                                <button wire:click="edit({{ $p->id }})" class="btn btn-white btn-sm border-0 text-secondary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="destroy({{ $p->id }})" wire:confirm="Hapus data?" class="btn btn-white btn-sm border-0 text-danger" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                            <p class="text-muted fw-medium">Tidak ada data peminjaman ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $peminjaman->links() }}
    </div>

    {{-- MODAL CRUD - PALET WARNA SEKOLAH --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(45,26,18,0.6); backdrop-filter: blur(8px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-sidebar border-0 text-white p-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi {{ $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                        {{ $isEdit ? 'Update Data' : 'Tambah Pinjaman' }}
                    </h5>
                    <button wire:click="closeModal" type="button" class="btn-close btn-close-white shadow-none"></button>
                </div>
                <div class="modal-body p-4 bg-soft">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="label-custom">Nama Siswa</label>
                            <select wire:model="user_id" class="form-select select-custom">
                                <option value="">Pilih Siswa...</option>
                                @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="label-custom">Buku</label>
                            <select wire:model="buku_id" class="form-select select-custom">
                                <option value="">Pilih Buku...</option>
                                @foreach($bukus as $buku) <option value="{{ $buku->id }}">{{ $buku->judul }} ({{ $buku->jumlah }})</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="label-custom">Tgl Pinjam</label>
                            <input type="date" wire:model="tgl_pinjam" class="form-control input-custom">
                        </div>
                        <div class="col-6">
                            <label class="label-custom">Tgl Kembali</label>
                            <input type="date" wire:model="tgl_kembali" class="form-control input-custom">
                        </div>
                        <div class="col-12">
                            <label class="label-custom">Jumlah Pinjam</label>
                            <input type="number" wire:model="jumlah_pinjam" class="form-control input-custom">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button wire:click="store" class="btn btn-orange w-100 rounded-3 fw-bold py-3 shadow-orange">
                            <i class="bi bi-save me-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- CSS CUSTOM UNTUK PALET WARNA --}}
    <style>
        :root {
            --primary-orange: #C05621;
            --sidebar-dark: #2D1A12;
            --bg-soft: #FDF8F5;
        }

        /* Search Bar Oval */
        .search-oval {
            border-radius: 50px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eee;
        }

        /* Colors & Buttons */
        .bg-sidebar { background-color: var(--sidebar-dark); }
        .text-orange { color: var(--primary-orange); }
        .bg-soft { background-color: var(--bg-soft); }
        
        .btn-orange {
            background-color: var(--primary-orange);
            color: white;
            border: none;
            transition: 0.3s;
        }
        .btn-orange:hover {
            background-color: #a0471b;
            color: white;
            transform: translateY(-2px);
        }

        .alert-custom {
            background-color: #fff;
            border-left: 5px solid var(--primary-orange) !important;
        }

        /* Modal & Form */
        .label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--sidebar-dark);
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .input-custom, .select-custom {
            background-color: #fff;
            border: 1.5px solid #eee;
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .input-custom:focus, .select-custom:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(192, 86, 33, 0.1);
        }

        .shadow-orange {
            box-shadow: 0 8px 20px rgba(192, 86, 33, 0.25);
        }

        .btn-white:hover {
            background-color: #f8f9fa;
        }
    </style>
</div>