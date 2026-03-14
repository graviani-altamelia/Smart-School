<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Manajemen Buku</h2>
            <p class="text-muted small">Kelola koleksi buku perpustakaan</p>
        </div>
        <input wire:model.live="search" type="text" class="form-control w-25 rounded-pill shadow-sm border-0 px-4" placeholder="Cari judul buku...">
    </div>

    @if(session()->has('pesan'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('pesan') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-journal-plus text-warning me-2"></i>
                    {{ $isEdit ? 'Edit Data Buku' : 'Tambah Buku Baru' }}
                </h5>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted">JUDUL BUKU</label>
                    <input wire:model="judul" type="text" class="form-control bg-light border-0 py-2">
                    @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted">PENULIS</label>
                    <input wire:model="penulis" type="text" class="form-control bg-light border-0 py-2">
                    @error('penulis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted">KATEGORI BUKU</label>
                    <select wire:model="kategori_id" class="form-select bg-light border-0 py-2">
                        <option value="">Pilih Kategori</option>
                        @foreach($semua_kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option> 
                        @endforeach
                    </select>
                    @error('kategori_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="small fw-bold text-muted">TAHUN</label>
                        <input wire:model="tahun" type="text" class="form-control bg-light border-0 py-2" placeholder="2024">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small fw-bold text-muted">STOK</label>
                        <input wire:model="jumlah" type="number" class="form-control bg-light border-0 py-2">
                        @error('jumlah') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-2">
                    <button wire:click="{{ $isEdit ? 'update' : 'simpan' }}" class="btn btn-warning w-100 py-2 fw-bold text-white shadow-sm rounded-3">
                        {{ $isEdit ? 'SIMPAN PERUBAHAN' : 'TAMBAHKAN BUKU' }}
                    </button>
                    @if($isEdit)
                        <button wire:click="resetInput" class="btn btn-link w-100 text-muted mt-1 text-decoration-none small">Batal</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted fw-bold">
                            <th class="ps-4 py-3">BUKU</th>
                            <th>KATEGORI</th>
                            <th class="text-center">STOK</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semua_buku as $buku)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $buku->judul }}</div>
                                <div class="text-muted small">{{ $buku->penulis ?? 'Anonim' }} | {{ $buku->tahun ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-info-subtle text-info px-3">
                                    {{ $buku->kategori->nama ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $buku->jumlah }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button wire:click="edit({{ $buku->id }})" class="btn btn-sm btn-light text-primary shadow-sm border me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button onclick="confirm('Hapus buku ini?') || event.stopImmediatePropagation()" wire:click="hapus({{ $buku->id }})" class="btn btn-sm btn-light text-danger shadow-sm border">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted italic">Data buku masih kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>