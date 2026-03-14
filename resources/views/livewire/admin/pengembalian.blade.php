<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Pengembalian Buku</h2>
            <p class="text-muted small">Waktu Sistem: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y') }}</p>
        </div>
        <div style="width: 300px;">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                <input wire:model.live="search" type="text" class="form-control border-0 ps-2 small py-2" placeholder="Cari siswa atau buku...">
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            @if($isEdit)
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Peminjaman</h5>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Tenggat Kembali</label>
                    <input wire:model="tgl_kembali" type="date" class="form-control bg-light border-0 py-2">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Jumlah Pinjam</label>
                    <input wire:model="jumlah_pinjam" type="number" class="form-control bg-light border-0 py-2">
                </div>
                <div class="mt-2">
                    <button wire:click="update" class="btn btn-warning w-100 py-2 fw-bold text-white shadow-sm rounded-3">SIMPAN</button>
                    <button wire:click="cancel" class="btn btn-link w-100 text-muted mt-1 text-decoration-none small">Batal</button>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light opacity-75 text-center">
                <i class="bi bi-info-circle fs-2 text-muted mb-3 d-block"></i>
                <p class="small text-muted mb-0">Denda otomatis: <strong>Rp 1.000 /buku /hari</strong></p>
            </div>
            @endif
        </div>

        <div class="{{ $isEdit ? 'col-lg-8' : 'col-lg-12' }}">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small fw-bold">
                        <tr>
                            <th class="ps-4 py-3">Siswa & Buku</th>
                            <th>Tenggat</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            @php
                                $tgl_target = \Carbon\Carbon::parse($loan->tgl_kembali)->startOfDay();
                                $hari_ini = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();
                                
                                // Hitung selisih hari secara paksa
                                $is_telat = $hari_ini->timestamp > $tgl_target->timestamp;
                                $jml_hari = $is_telat ? (int) ceil(($hari_ini->timestamp - $tgl_target->timestamp) / 86400) : 0;
                                $denda_rp = $jml_hari * 1000 * ($loan->jumlah_pinjam ?? 1);
                            @endphp
                            
                            <tr class="{{ $is_telat ? 'table-danger' : '' }}">
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $loan->user->name ?? 'User' }}</div>
                                    <div class="text-muted small">{{ $loan->judul }} ({{ $loan->jumlah_pinjam }} eks)</div>
                                </td>
                                <td>
                                    <div class="small {{ $is_telat ? 'text-danger fw-bold' : '' }}">
                                        {{ $tgl_target->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($is_telat)
                                        <span class="badge rounded-pill bg-danger">Terlambat {{ $jml_hari }} Hari</span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3">Aman</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-danger">
                                    {{ $denda_rp > 0 ? 'Rp ' . number_format($denda_rp, 0, ',', '.') : 'Rp 0' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <button wire:click="edit({{ $loan->id }})" class="btn btn-sm btn-light text-primary border"><i class="bi bi-pencil-fill"></i></button>
                                        <button wire:click="kembalikan({{ $loan->id }})" 
                                                wire:confirm="Proses pengembalian? Denda: Rp {{ number_format($denda_rp, 0, ',', '.') }}"
                                                class="btn btn-sm btn-light text-success border"><i class="bi bi-check-lg fw-bold"></i></button>
                                        <button wire:click="delete({{ $loan->id }})" wire:confirm="Hapus?" class="btn btn-sm btn-light text-danger border"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5">Tidak ada peminjaman aktif.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $loans->links() }}</div>
        </div>
    </div>
</div>