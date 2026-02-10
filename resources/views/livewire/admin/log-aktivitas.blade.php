<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-0">Aktivitas Log</h2>
        <p class="text-muted">Riwayat seluruh aktivitas sistem Smart School</p>
    </div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Waktu</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted small">
                                {{ $log->created_at->format('d M Y') }} <br>
                                {{ $log->created_at->format('H:i') }} WIB
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $log->user->name ?? 'System' }}</span>
                        </td>
                        <td>
                            @php
                                $badgeColor = match($log->aksi) {
                                    'Tambah' => 'bg-success',
                                    'Kembali' => 'bg-info',
                                    'Pinjam' => 'bg-warning text-dark',
                                    'Hapus' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }} rounded-pill px-3">{{ $log->aksi }}</span>
                        </td>
                        <td><span class="text-muted">{{ $log->kategori }}</span></td>
                        <td><small>{{ $log->deskripsi }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>