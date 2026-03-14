<div class="container py-4">
    {{-- Header Section --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h3 class="fw-bold text-dark mb-1">Riwayat Pengembalian ✅</h3>
            <p class="text-muted small mb-0">Manajemen data buku yang telah dikembalikan oleh siswa.</p>
        </div>
        <div class="col-md-5">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" wire:model.live="search" class="form-control border-0 shadow-none px-2" placeholder="Cari nama atau judul buku...">
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-journal-check text-success fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold" style="font-size: 11px;">TOTAL KEMBALI</small>
                        <h4 class="fw-bold mb-0">{{ $riwayat->total() }} Buku</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-3">
                        <i class="bi bi-cash-stack text-danger fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold" style="font-size: 11px;">DENDA TERKUMPUL</small>
                        <h4 class="fw-bold mb-0 text-danger">Rp{{ number_format($total_denda, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">{{ session('message') }}</div>
    @endif

    {{-- Main Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small fw-bold text-muted">
                        <th class="ps-4 py-3">SISWA</th>
                        <th>BUKU</th>
                        <th class="text-center">TGL PINJAM</th>
                        <th class="text-center">TGL KEMBALI</th>
                        <th class="text-center">DENDA</th>
                        <th class="text-center pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $row)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                            <small class="text-muted">NIS: {{ $row->user->nis ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $row->judul }}</div>
                            <small class="text-muted">{{ $row->jumlah_pinjam }} Eks</small>
                        </td>
                        <td class="text-center small">{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <div class="small fw-bold">{{ \Carbon\Carbon::parse($row->tgl_kembali_asli)->format('d/m/Y H:i') }}</div>
                            @if($row->denda > 0)
                                <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 9px;">Terlambat</span>
                            @else
                                <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 9px;">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold {{ $row->denda > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $row->denda > 0 ? 'Rp'.number_format($row->denda, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-1">
                                <button wire:click="edit({{ $row->id }})" class="btn btn-sm btn-outline-primary border-0 rounded-circle" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button wire:click="destroy({{ $row->id }})" wire:confirm="Hapus data riwayat ini?" class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-muted display-4"></i>
                            <p class="text-muted mt-2">Tidak ada data pengembalian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $riwayat->links() }}</div>

    {{-- Edit Modal --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Riwayat</h5>
                    <button wire:click="closeModal" type="button" class="btn-close shadow-none"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small fw-bold text-secondary">Tanggal Dikembalikan</label>
                            <input type="datetime-local" wire:model="tgl_kembali_asli" class="form-control border-0 bg-light shadow-none">
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-secondary">Besar Denda (Rp)</label>
                            <input type="number" wire:model="denda" class="form-control border-0 bg-light shadow-none">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button wire:click="update" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">
                            UPDATE RIWAYAT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>