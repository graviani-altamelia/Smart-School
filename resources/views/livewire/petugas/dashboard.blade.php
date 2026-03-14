<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm rounded-4 text-white position-relative mb-4" 
         style="background: linear-gradient(135deg, #2D1A12 0%, #4a2d1f 100%) !important;">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 20;">
                <div>
                    <h2 class="fw-bold mb-1">   Halo Petugas Perpustakaan 👋</h2>
                    <p class="mb-0 opacity-75 fs-5">
                        Terdapat <span class="badge bg-warning text-dark rounded-pill px-3">{{ $permintaan_pending->count() }}</span> antrean validasi hari ini.
                    </p>
                </div>
                
                {{-- Dropdown Cetak Laporan - Style Admin --}}
                <div class="dropdown">
                    <button class="btn btn-warning btn-lg rounded-pill px-4 dropdown-toggle shadow-sm fw-bold text-white" 
                            type="button" id="dropdownCetak" data-bs-toggle="dropdown" aria-expanded="false" 
                            style="z-index: 30;">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                    </button>
                    {{-- Dropdown Menu dengan Z-Index tertinggi --}}
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 rounded-4" 
                        aria-labelledby="dropdownCetak" 
                        style="z-index: 9999 !important; position: absolute;">
                        <li><a class="dropdown-item rounded-3 py-2 fw-medium text-dark" href="{{ route('laporan.peminjaman', ['filter' => 'mingguan']) }}" target="_blank">Laporan Mingguan</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 fw-medium text-dark" href="{{ route('laporan.peminjaman', ['filter' => 'bulanan']) }}" target="_blank">Laporan Bulanan</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li><a class="dropdown-item rounded-3 py-2 text-danger fw-bold" href="{{ route('laporan.peminjaman', ['filter' => 'semua']) }}" target="_blank">Cetak Semua Data</a></li>
                    </ul>
                </div>
            </div>
            
            {{-- Dekorasi Ikon - Diberi pointer-events: none agar tidak menghalangi klik --}}
            <div class="position-absolute end-0 top-50 translate-middle-y opacity-10 d-none d-md-block" 
                 style="margin-right: 30px; pointer-events: none; z-index: 1;">
                <i class="bi bi-journal-check" style="font-size: 150px;"></i>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
        </div>
    @endif

    <div class="row">
        {{-- Form Edit (Gaya Admin) --}}
        @if($isEdit)
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-pencil-square text-warning me-2"></i> Edit Tenggat
                </h5>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">TANGGAL KEMBALI</label>
                    <input wire:model="tgl_kembali" type="date" class="form-control bg-light border-0 py-2">
                </div>
                <button wire:click="update" class="btn btn-warning w-100 py-2 fw-bold text-white shadow-sm rounded-3">
                    SIMPAN PERUBAHAN
                </button>
                <button wire:click="$set('isEdit', false)" class="btn btn-link w-100 text-muted mt-1 text-decoration-none small">Batal</button>
            </div>
        </div>
        @endif

        {{-- Tabel Antrean (Aksi Persis Admin) --}}
        <div class="{{ $isEdit ? 'col-lg-8' : 'col-lg-12' }}">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="table-responsive rounded-4">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted fw-bold">
                                <th class="ps-4 py-3">SISWA</th>
                                <th>BUKU</th>
                                <th class="text-center">TENGGAT</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permintaan_pending as $row)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $row->user->name }}</div>
                                    <div class="text-muted small">NIS: {{ $row->user->nis ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $row->judul }}</div>
                                    <span class="badge rounded-pill bg-info-subtle text-info px-3 small">
                                        Stok: {{ $row->buku->jumlah ?? 0 }}
                                    </span>
                                </td>
                                <td class="text-center small fw-bold text-muted">
                                    {{ date('d/m/Y', strtotime($row->tgl_kembali)) }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        {{-- Setuju - Putih Ikon Hijau --}}
                                        <button wire:click="setujui({{ $row->id }})" class="btn btn-sm btn-light text-success shadow-sm border me-1">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                        {{-- Edit - Putih Ikon Biru --}}
                                        <button wire:click="edit({{ $row->id }})" class="btn btn-sm btn-light text-primary shadow-sm border me-1">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        {{-- Hapus - Putih Ikon Merah --}}
                                        <button onclick="confirm('Hapus?') || event.stopImmediatePropagation()" 
                                                wire:click="hapus({{ $row->id }})" class="btn btn-sm btn-light text-danger shadow-sm border">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                            <p class="text-muted fw-medium">Tidak ada data antrean validasi peminjaman</p>
                        </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>