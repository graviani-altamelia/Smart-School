<div class="container py-4">
    <div class="row g-4">
        {{-- Header --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Riwayat Pengembalian Buku ✅</h4>
                        <p class="text-muted mb-0">Daftar buku yang telah dikembalikan oleh siswa.</p>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group rounded-pill border bg-light px-3 py-1">
                            <span class="input-group-text bg-transparent border-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" wire:model.live="search" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari nama siswa...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Singkat --}}
        <div class="col-md-12">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-success bg-opacity-10">
                        <small class="text-success fw-bold text-uppercase" style="font-size: 10px;">Total Pengembalian</small>
                        <h3 class="fw-bold mb-0 text-success">{{ $riwayat->total() }} <small class="fs-6 fw-normal">Buku</small></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-danger bg-opacity-10">
                        <small class="text-danger fw-bold text-uppercase" style="font-size: 10px;">Total Denda Terkumpul</small>
                        <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($riwayat->sum('denda'), 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small">
                                    <th class="border-0 ps-4 py-3">SISWA</th>
                                    <th class="border-0 py-3">BUKU</th>
                                    <th class="border-0 text-center py-3">TGL HARUS KEMBALI</th>
                                    <th class="border-0 text-center py-3">DIKEMBALIKAN PADA</th>
                                    <th class="border-0 text-end py-3 pe-4">DENDA TERBAYAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $row)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                                        <small class="text-muted">NIS: {{ $row->user->nis ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $row->buku->judul }}</div>
                                        <small class="text-muted">{{ $row->jumlah_pinjam }} Eks</small>
                                    </td>
                                    <td class="text-center small">
                                        {{ \Carbon\Carbon::parse($row->tgl_kembali)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-bold text-success small">
                                            {{ \Carbon\Carbon::parse($row->tgl_kembali_asli)->format('d/m/Y H:i') }}
                                        </div>
                                        @if(\Carbon\Carbon::parse($row->tgl_kembali_asli)->gt(\Carbon\Carbon::parse($row->tgl_kembali)))
                                            <span class="badge bg-danger" style="font-size: 9px;">Terlambat</span>
                                        @else
                                            <span class="badge bg-success" style="font-size: 9px;">Tepat Waktu</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($row->denda > 0)
                                            <span class="text-danger fw-bold">Rp {{ number_format($row->denda, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted small italic">Nihil</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-folder2-open display-1 text-muted opacity-25"></i>
                                        <p class="text-muted mt-3">Belum ada riwayat pengembalian.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    {{ $riwayat->links() }}
                </div>
            </div>
        </div>
    </div>
</div>