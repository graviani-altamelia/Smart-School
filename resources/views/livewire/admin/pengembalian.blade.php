<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Pengembalian Buku</h2>
            <p class="text-muted">Kelola pengembalian dan hitung denda keterlambatan</p>
        </div>
        {{-- Menambahkan Input Search agar Admin mudah mencari data --}}
        <div style="width: 300px;">
            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                <input type="text" wire:model.live="search" class="form-control border-0 ps-0" placeholder="Cari siswa/buku...">
            </div>
        </div>
    </div>

    {{-- Alert sukses menggunakan session --}}
    @if (session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
            <div>{{ session('message') }}</div>
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
                <tbody wire:loading.class="opacity-50"> {{-- Efek loading saat proses --}}
                    @forelse($loans as $loan)
                    <tr wire:key="loan-row-{{ $loan->id }}">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $loan->user->name ?? 'User Dihapus' }}</div>
                            <small class="text-muted">NIS: {{ $loan->user->nis ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $loan->buku->judul ?? $loan->judul }}</div>
                        </td>
                        <td>
                            <span class="text-secondary">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($loan->tgl_kembali)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            @if($loan->terlambat > 0)
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                    <i class="bi bi-exclamation-triangle me-1"></i> {{ $loan->terlambat }} Hari
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-all me-1"></i> Tepat Waktu
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold {{ ($loan->denda ?? 0) > 0 ? 'text-danger' : 'text-dark' }}">
                                Rp {{ number_format($loan->denda ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="text-center">
                            <button wire:click="kembalikan({{ $loan->id }})" 
                                    wire:confirm="Proses pengembalian buku ini?"
                                    wire:loading.attr="disabled"
                                    class="btn btn-success btn-sm rounded-pill px-3">
                                <span wire:loading.remove wire:target="kembalikan({{ $loan->id }})">
                                    <i class="bi bi-arrow-left-right me-1"></i> Kembalikan
                                </span>
                                <span wire:loading wire:target="kembalikan({{ $loan->id }})">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard-x fs-1 d-block mb-3 opacity-25"></i>
                            Belum ada buku yang perlu dikembalikan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($loans, 'links'))
            <div class="p-3 bg-light border-top">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>