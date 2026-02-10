<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Riwayat Peminjaman</h3>
            <p class="text-muted">Pantau status buku yang kamu pinjam di sini.</p>
        </div>
        <a href="{{ route('home') }}" wire:navigate class="btn btn-outline-primary rounded-pill">
            <i class="bi bi-plus-lg"></i> Pinjam Buku Lagi
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $item->buku->judul }}</div>
                            <small class="text-muted">{{ $item->buku->penulis }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'dipinjam')
                                <span class="badge bg-warning-subtle text-warning px-3 rounded-pill">Sedang Dipinjam</span>
                            @else
                                <span class="badge bg-success-subtle text-success px-3 rounded-pill">Sudah Kembali</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/reading-book.svg" alt="Empty" style="height: 150px;">
                            <p class="mt-3 text-muted">Kamu belum pernah meminjam buku.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>