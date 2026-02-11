<div>
    <header class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Ringkasan Statistik</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}.</p>
        </div>
        {{-- Tombol aksi cepat --}}
        <a href="{{ route('admin.pinjam') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Pinjam Baru
        </a>
    </header>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 rounded-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Total Siswa</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalSiswa) }}</h3>
                        <small class="text-primary fw-medium">Siswa Aktif</small>
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
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Koleksi Buku</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalBuku) }}</h3>
                        <small class="text-success fw-medium">Total Stok</small>
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
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Dipinjam</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalPinjam) }}</h3>
                        <small class="text-warning fw-medium">Belum Kembali</small>
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
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Terlambat</p>
                        <h3 class="fw-bold mb-0 text-danger">{{ number_format($terlambat) }}</h3>
                        <small class="text-danger fw-medium">Jatuh Tempo</small>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h5 class="fw-bold mb-4">Aktivitas Peminjaman Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th class="border-0">NAMA SISWA</th>
                        <th class="border-0">JUDUL BUKU</th>
                        <th class="border-0">TANGGAL PINJAM</th>
                        <th class="border-0 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $item)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->user->name ?? 'User Tak Dikenal' }}</div>
                            <small class="text-muted">NIS: {{ $item->user->nis ?? '-' }}</small>
                        </td>
                        <td class="text-primary fw-medium">{{ $item->buku->judul ?? $item->judul }}</td>
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ $item->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            {{-- Penyesuaian nama kolom status_peminjaman --}}
                            @php
                                $isDipinjam = ($item->status_peminjaman ?? $item->status) == 'dipinjam';
                            @endphp
                            <span class="badge rounded-pill px-3 py-2 {{ $isDipinjam ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }}">
                                <i class="bi bi-{{ $isDipinjam ? 'arrow-repeat' : 'check-circle' }} me-1"></i>
                                {{ ucfirst($item->status_peminjaman ?? $item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                            Belum ada aktivitas peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>