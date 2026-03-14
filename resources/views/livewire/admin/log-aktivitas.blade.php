<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Aktivitas Sistem</h2>
            <p class="text-muted small">Riwayat real-time aktivitas Petugas dan Siswa</p>
        </div>
        
        <div style="width: 300px;">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border bg-white">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input wire:model.live="search" type="text" class="form-control border-0 ps-2 small py-2" placeholder="Cari aktivitas...">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted fw-bold">
                        <th class="ps-4 py-3">WAKTU</th>
                        <th>PENGGUNA</th>
                        <th>AKSI</th>
                        <th>KATEGORI</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark small">{{ $log->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-muted extra-small" style="font-size: 11px;">{{ $log->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px; font-size: 12px; border: 1px solid #ffc107;">
                                    {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark small">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeColor = match($log->aksi) {
                                    'Tambah' => 'bg-success-subtle text-success',
                                    'Kembali' => 'bg-info-subtle text-info',
                                    'Pinjam' => 'bg-warning-subtle text-warning',
                                    'Hapus' => 'bg-danger-subtle text-danger',
                                    default => 'bg-light text-muted'
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 {{ $badgeColor }}" style="font-size: 10px; border: 1px solid currentColor;">
                                {{ strtoupper($log->aksi) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 10px;">{{ $log->kategori }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">{{ $log->deskripsi }}</span>
                            <div style="font-size: 10px;" class="text-light-emphasis mt-1 italic">IP: {{ $log->ip_address }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-database-exclamation fs-1 d-block mb-2 opacity-25"></i>
                            <p class="italic">Belum ada jejak aktivitas terdeteksi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-3 bg-light border-top">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>