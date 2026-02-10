<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Data Peminjaman</h3>
            <p class="text-muted">Kelola riwayat peminjaman buku perpustakaan.</p>
        </div>
        
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $pinjam)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $pinjam->user->name ?? 'User Tidak Ada' }}</span>
                            </td>
                            <td>{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d M Y') }}</td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $pinjam->status == 'dipinjam' ? 'warning' : 'success' }}">
                                    {{ ucfirst($pinjam->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border">Detail</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Tidak ada data peminjaman saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>