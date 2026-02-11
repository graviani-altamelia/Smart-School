<div class="container py-4">
    <div class="row g-4">
        {{-- Header Section --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Daftar Peminjaman Aktif 📖</h4>
                        <p class="text-muted mb-0">Memantau buku yang sedang dibawa oleh siswa.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group rounded-pill border bg-light px-3 py-1">
                            <span class="input-group-text bg-transparent border-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" wire:model.live="search" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari nama siswa...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="border-0 ps-4 py-3">PEMINJAM</th>
                                    <th class="border-0 py-3">JUDUL BUKU</th>
                                    <th class="border-0 py-3">TGL PINJAM</th>
                                    <th class="border-0 py-3">TENGGAT</th>
                                    <th class="border-0 text-center py-3">STATUS</th>
                                    <th class="border-0 text-center py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman as $row)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                                        <small class="text-muted">ID: #{{ $row->id }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-primary">{{ $row->buku->judul }}</div>
                                        <small class="text-muted">{{ $row->jumlah_pinjam }} Eks</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $tenggat = \Carbon\Carbon::parse($row->tgl_kembali);
                                            $terlambat = now() > $tenggat;
                                        @endphp
                                        <span class="{{ $terlambat ? 'text-danger fw-bold' : 'text-dark' }}">
                                            {{ $tenggat->format('d/m/Y') }}
                                        </span>
                                        @if($terlambat)
                                            <div class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 10px;">TERLAMBAT</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Sedang Dipinjam</span>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="kembalikan({{ $row->id }})" 
                                                wire:confirm="Proses pengembalian buku ini?"
                                                class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-arrow-return-left me-1"></i> Kembalikan
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/white/reading-book.svg" alt="no data" style="width: 150px;" class="mb-3 opacity-50">
                                        <p class="text-muted fw-medium">Tidak ada buku yang sedang dipinjam saat ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>