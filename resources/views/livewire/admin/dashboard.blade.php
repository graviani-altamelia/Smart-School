<div>
    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0">Statistik Perpustakaan</h2>
        <p class="text-muted">Pantau aktivitas peminjaman dan koleksi buku secara real-time.</p>
    </div>

    {{-- Grid Kartu Statistik --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded-4 p-3 me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalSiswa }}</h3>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Siswa Terdaftar</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success rounded-4 p-3 me-3">
                        <i class="bi bi-book-half fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalBuku }}</h3>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Stok Tersedia</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning rounded-4 p-3 me-3">
                        <i class="bi bi-arrow-left-right fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalPinjam }}</h3>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Buku Keluar (Dipinjam)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-danger text-white h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-4 p-3 me-3">
                        <i class="bi bi-alarm fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $terlambat }}</h3>
                        <small class="text-uppercase fw-bold" style="font-size: 0.7rem;">Melewati Tenggat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Tabel Aktivitas Terbaru --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Aktivitas Terbaru</h5>
                        <a href="/admin/pinjam" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small uppercase">
                                <th class="ps-4 py-3">Peminjam</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tenggat</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aktivitas as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->user->name ?? 'User Tak Dikenal' }}</div>
                                    <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-medium text-primary">{{ $item->judul }}</div>
                                    <small class="text-muted">Jumlah: {{ $item->jumlah_pinjam }} eks</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $isOverdue = \Carbon\Carbon::parse($item->tgl_kembali)->isPast() && $item->status_peminjaman != 'dikembalikan';
                                    @endphp
                                    <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                        {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->status_peminjaman == 'pending')
                                        <span class="badge rounded-pill px-3 py-2 bg-secondary-subtle text-secondary">Pending</span>
                                    @elseif($item->status_peminjaman == 'dipinjam')
                                        @if(\Carbon\Carbon::parse($item->tgl_kembali)->isPast())
                                            <span class="badge rounded-pill px-3 py-2 bg-danger-subtle text-danger">Terlambat</span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-2 bg-warning-subtle text-warning">Dipinjam</span>
                                        @endif
                                    @elseif($item->status_peminjaman == 'dikembalikan')
                                        <span class="badge rounded-pill px-3 py-2 bg-success-subtle text-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada aktivitas hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>