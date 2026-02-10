<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-0">Pengembalian Buku</h2>
        <p class="text-muted">Kelola pengembalian dan hitung denda keterlambatan</p>
    </div>
    @if (session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Peminjam</th>
                        <th>Buku</th>
                        <th>Tenggat</th>
                        <th>Keterlambatan</th>
                        <th>Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $loan->user->name }}</div>
                            <small class="text-muted">Siswa</small>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $loan->buku->judul }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($loan->tgl_kembali)->format('d/m/Y') }}</td>
                        <td>
                            @if($loan->terlambat > 0)
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                    {{ $loan->terlambat }} Hari
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Tepat Waktu</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold {{ $loan->denda > 0 ? 'text-danger' : 'text-dark' }}">
                                Rp {{ number_format($loan->denda, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="text-center">
                            <button wire:click="kembalikan({{ $loan->id }})" 
                                    onclick="confirm('Proses pengembalian?') || event.stopImmediatePropagation()"
                                    class="btn btn-success btn-sm rounded-3">
                                <i class="bi bi-arrow-left-right me-1"></i> Kembalikan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard-check fs-1 d-block mb-3"></i>
                            Tidak ada buku yang sedang dipinjam.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>