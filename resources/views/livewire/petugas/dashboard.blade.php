<div class="container py-4">
    <div class="row g-4">
        {{-- Hero Section --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white position-relative">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                        <div>
                            <h2 class="fw-bold mb-1">Halo, Petugas Perpustakaan! 👋</h2>
                            <p class="mb-0 opacity-75 fs-5">
                                Terdeteksi <span class="badge bg-white text-primary rounded-pill px-3">{{ $permintaan_pending->count() }}</span> antrean validasi hari ini.
                            </p>
                        </div>
                        
                        {{-- Dropdown Cetak Laporan --}}
                        <div class="dropdown">
                            <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle shadow-sm fw-bold text-primary" 
                                    type="button" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 rounded-4">
                                <li>
                                    <a class="dropdown-item rounded-3 py-2" href="{{ route('laporan.peminjaman', ['filter' => 'mingguan']) }}" target="_blank">
                                        <i class="bi bi-calendar-week me-2 text-primary"></i> Laporan Mingguan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2" href="{{ route('laporan.peminjaman', ['filter' => 'bulanan']) }}" target="_blank">
                                        <i class="bi bi-calendar-month me-2 text-primary"></i> Laporan Bulanan
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 text-danger fw-bold" href="{{ route('laporan.peminjaman', ['filter' => 'semua']) }}" target="_blank">
                                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Cetak Semua Data
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    {{-- Dekorasi Ikon Latar Belakang --}}
                    <div class="position-absolute end-0 top-50 translate-middle-y opacity-10 d-none d-md-block" style="margin-right: 30px;">
                        <i class="bi bi-journal-check" style="font-size: 180px;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="col-md-12">
            @if (session()->has('message'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Tabel Antrean --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 p-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4 me-3">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Antrean Validasi Peminjaman</h4>
                            <p class="text-muted small mb-0">Segera proses permintaan siswa agar buku dapat diambil.</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th class="border-0 ps-3 py-3">INFORMASI SISWA</th>
                                    <th class="border-0 py-3">BUKU YANG DIPILIH</th>
                                    <th class="border-0 text-center py-3">TGL KEMBALI</th>
                                    <th class="border-0 text-center py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($permintaan_pending as $row)
                                <tr>
                                    <td class="ps-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                                                <small class="text-muted">NIS: {{ $row->user->nis ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-primary fw-bold">{{ $row->buku->judul ?? $row->judul }}</div>
                                        <small class="text-muted">Jumlah: {{ $row->jumlah_pinjam ?? 1 }} Eks</small>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-light text-dark border-0 shadow-sm fw-normal px-3 py-2 rounded-pill">
                                            <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($row->tgl_kembali)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <div class="btn-group gap-2">
                                            <button wire:click="setujui({{ $row->id }})" 
                                                    class="btn btn-success rounded-pill px-3 btn-sm d-flex align-items-center shadow-sm">
                                                <i class="bi bi-check-lg me-1"></i> Setuju
                                            </button>
                                            <button wire:click="tolak({{ $row->id }})" 
                                                    wire:confirm="Tolak permintaan ini?" 
                                                    class="btn btn-outline-danger rounded-pill px-3 btn-sm d-flex align-items-center shadow-sm">
                                                <i class="bi bi-x-lg me-1"></i> Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted opacity-25 mb-3">
                                            <i class="bi bi-clipboard2-x" style="font-size: 80px;"></i>
                                        </div>
                                        <h5 class="fw-bold text-muted">Yeay! Antrean Kosong</h5>
                                        <p class="text-muted small">Semua permintaan peminjaman sudah diproses.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>