<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Data Peminjaman</h3>
            <p class="text-muted">Kelola riwayat peminjaman buku perpustakaan.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th> <th>Status</th>
                            <th>Denda</th> <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $pinjam)
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $pinjam->user->name ?? 'User Tidak Ada' }}</span>
                            </td>
                            <td>{{ $pinjam->buku->judul ?? 'Buku Dihapus' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M Y') }}</td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $pinjam->status_peminjaman == 'dipinjam' ? 'warning text-dark' : 'success' }}">
                                    {{ ucfirst($pinjam->status_peminjaman) }}
                                </span>
                            </td>
                            <td>
                                @if($pinjam->status_peminjaman == 'dipinjam')
                                    @php $denda = $pinjam->hitungDenda(); @endphp
                                    @if($denda > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted small">Tidak ada denda</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($pinjam->status_peminjaman == 'dipinjam')
                                    <button wire:click="kembalikanBuku({{ $pinjam->id }})" 
                                            wire:confirm="Konfirmasi buku sudah dikembalikan?"
                                            class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-arrow-return-left me-1"></i> Selesai Pinjam
                                    </button>
                                @else
                                    <button wire:click="hapusRiwayat({{ $pinjam->id }})" 
                                            wire:confirm="Hapus riwayat ini?"
                                            class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
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