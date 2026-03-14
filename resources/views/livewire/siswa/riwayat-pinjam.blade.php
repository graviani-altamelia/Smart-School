<div class="container py-5">
    <style>
        .history-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #fff; overflow: hidden; }
        .table thead th { background: #f8f9fa; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #777; padding: 15px 20px; }
        .table tbody td { padding: 15px 20px; border-bottom: 1px solid #f1f1f1; }
        .status-badge { padding: 5px 12px; font-weight: 700; font-size: 0.65rem; border-radius: 8px; text-transform: uppercase; display: inline-flex; align-items: center; }
        .text-orange { color: #d67d3e; }
        .btn-earth { background: #d67d3e; color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; border: none; padding: 10px 20px; text-decoration: none; }
        .btn-earth:hover { background: #bc6a31; color: white; transform: translateY(-2px); }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0 text-dark">Riwayat Peminjaman</h3>
            <p class="text-muted small">Pantau status buku dan tagihan denda kamu.</p>
        </div>
        <a href="{{ route('home') }}" wire:navigate class="btn btn-earth">
            <i class="bi bi-plus-lg me-2"></i> Pinjam Buku
        </a>
    </div>

    <div class="card history-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead>
                    <tr>
                        <th style="min-width: 200px;">Buku</th>
                        <th class="text-center">Qty</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th class="text-end">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        @php 
                            $dendaReal = $item->hitungDenda();
                            // Lewat jadwal jika: Hari ini > Tanggal Kembali DAN belum dikembalikan
                            $lewatJadwal = \Carbon\Carbon::now()->startOfDay()->gt($item->tgl_kembali->startOfDay()) && $item->status_peminjaman != 'dikembalikan';
                            // Jatuh tempo hari ini
                            $hariIniTenggat = \Carbon\Carbon::now()->startOfDay()->eq($item->tgl_kembali->startOfDay()) && $item->status_peminjaman != 'dikembalikan';
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->buku->judul ?? $item->judul }}</div>
                                <div class="text-muted small">{{ $item->buku->penulis ?? 'Penulis tidak diketahui' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $item->jumlah_pinjam }}</span>
                            </td>
                            <td>
                                <div class="small fw-bold {{ $lewatJadwal ? 'text-danger' : ($hariIniTenggat ? 'text-warning' : '') }}">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $item->tgl_kembali->format('d M Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem;">
                                    Dipinjam: {{ $item->tgl_pinjam->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                @if($item->status_peminjaman == 'pending')
                                    <span class="status-badge bg-secondary-subtle text-secondary"><i class="bi bi-clock me-1"></i> Menunggu</span>
                                @elseif($item->status_peminjaman == 'dikembalikan')
                                    <span class="status-badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                @elseif($lewatJadwal)
                                    <span class="status-badge bg-danger text-white shadow-sm"><i class="bi bi-exclamation-triangle me-1"></i> Terlambat</span>
                                @elseif($hariIniTenggat)
                                    <span class="status-badge bg-warning text-dark"><i class="bi bi-info-circle me-1"></i> Hari Terakhir</span>
                                @else
                                    <span class="status-badge bg-info-subtle text-info"><i class="bi bi-book me-1"></i> Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($dendaReal > 0)
                                    <div class="text-danger fw-bold">Rp {{ number_format($dendaReal, 0, ',', '.') }}</div>
                                    <small class="text-muted fw-bold" style="font-size: 0.6rem;">WAJIB BAYAR</small>
                                @elseif($lewatJadwal)
                                    <div class="text-danger small fw-bold uppercase">Proses Denda...</div>
                                    <small class="text-muted" style="font-size: 0.6rem;">Segera Kembalikan!</small>
                                @elseif($hariIniTenggat)
                                    <div class="text-warning small fw-bold">Rp 0</div>
                                    <small class="text-muted" style="font-size: 0.6rem;">Kembalikan Hari Ini</small>
                                @else
                                    <div class="text-success small fw-bold">Rp 0</div>
                                    <small class="text-muted" style="font-size: 0.6rem;">Aman</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/reading-book.svg" style="height: 120px; opacity: 0.5;">
                                <p class="mt-3 text-muted">Belum ada riwayat peminjaman.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>