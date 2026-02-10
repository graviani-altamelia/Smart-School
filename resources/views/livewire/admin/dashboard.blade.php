<div>
    <header class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0">Ringkasan Statistik</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}.</p>
        </div>
    </header>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold">Total Siswa</p>
                        <h3 class="fw-bold mb-0">{{ $totalSiswa }}</h3>
                        <small class="text-primary">Siswa Aktif</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold">Koleksi Buku</p>
                        <h3 class="fw-bold mb-0">{{ $totalBuku }}</h3>
                        <small class="text-success">Total Stok</small>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="bi bi-book fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold">Dipinjam</p>
                        <h3 class="fw-bold mb-0">{{ $totalPinjam }}</h3>
                        <small class="text-warning">Belum Kembali</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                        <i class="bi bi-journal-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold">Terlambat</p>
                        <h3 class="fw-bold mb-0 text-danger">{{ $terlambat }}</h3>
                        <small class="text-danger">Jatuh Tempo</small>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h5 class="fw-bold mb-3">Aktivitas Peminjaman Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="small">
                        <th>NAMA SISWA</th>
                        <th>JUDUL BUKU</th>
                        <th>TANGGAL PINJAM</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $item)
                    <tr>
                        <td><strong>{{ $item->user->name ?? 'User Tak Dikenal' }}</strong></td>
                        <td>{{ $item->buku->judul ?? 'Buku Dihapus' }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge rounded-pill bg-{{ $item->status == 'dipinjam' ? 'warning' : 'success' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada aktivitas hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>