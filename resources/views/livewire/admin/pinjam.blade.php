<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Data Peminjaman</h2>
            <p class="text-muted small">Kelola status dan tenggat pengembalian buku</p>
        </div>
        
        <div style="width: 300px;">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input wire:model.live="search" type="text" 
                       class="form-control border-0 ps-2 small py-2" 
                       placeholder="Cari nama siswa/judul buku...">
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            @if($isEdit)
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    Edit Peminjaman
                </h5>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Tenggat Kembali</label>
                    <input wire:model="tgl_kembali" type="date" class="form-control bg-light border-0 py-2">
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Status</label>
                    <select wire:model="status_peminjaman" class="form-select bg-light border-0 py-2">
                        <option value="dipinjam">Dipinjam</option>
                        <option value="dikembalikan">Dikembalikan</option>
                    </select>
                </div>

                <div class="mt-2">
                    <button wire:click="update" class="btn btn-warning w-100 py-2 fw-bold text-white shadow-sm rounded-3">
                        SIMPAN PERUBAHAN
                    </button>
                    <button wire:click="resetInput" class="btn btn-link w-100 text-muted mt-1 text-decoration-none small">Batal Edit</button>
                </div>
            </div>
            @endif
        </div>

        <div class="{{ $isEdit ? 'col-lg-8' : 'col-lg-12' }}">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted fw-bold">
                            <th class="ps-4 py-3">SISWA & BUKU</th>
                            <th>TENGGAT</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $p->user->name ?? 'User' }}</div>
                                <div class="text-muted small">{{ $p->judul }}</div>
                            </td>
                            <td>
                                <div class="small {{ $p->is_terlambat && $p->status_peminjaman != 'dikembalikan' ? 'text-danger fw-bold' : 'text-dark' }}">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->status_peminjaman == 'dikembalikan')
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3">Selesai</span>
                                @else
                                    <span class="badge rounded-pill {{ $p->is_terlambat ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }} px-3">
                                        {{ ucfirst($p->status_peminjaman) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-light text-primary shadow-sm border me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    @if($p->status_peminjaman != 'dikembalikan')
                                    <button wire:click="kembalikanBuku({{ $p->id }})" class="btn btn-sm btn-light text-success shadow-sm border me-1">
                                        <i class="bi bi-check-lg fw-bold"></i>
                                    </button>
                                    @endif

                                    <button onclick="confirm('Hapus?') || event.stopImmediatePropagation()" 
                                            wire:click="hapusRiwayat({{ $p->id }})" 
                                            class="btn btn-sm btn-light text-danger shadow-sm border">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data peminjaman tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>