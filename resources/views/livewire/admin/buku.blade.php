<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Kelola Koleksi Buku</h2>
            <p class="text-muted">Input dan monitor stok buku perpustakaan</p>
        </div>
        
        @if (session()->has('message'))
            <div class="alert alert-success py-2 px-4 shadow-sm rounded-pill mb-0">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 p-4 rounded-4">
                <h5 class="fw-bold mb-3">Tambah Buku Baru</h5>
                <form wire:submit.prevent="store">
                    <div class="mb-3">
                        <label class="small fw-bold">Judul Buku</label>
                        <input type="text" class="form-control rounded-3" wire:model="judul" placeholder="Masukkan judul lengkap">
                        @error('judul') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold">Kategori</label>
                        <select class="form-select rounded-3" wire:model="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Penulis</label>
                            <input type="text" class="form-control rounded-3" wire:model="penulis">
                            @error('penulis') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Penerbit</label>
                            <input type="text" class="form-control rounded-3" wire:model="penerbit">
                            @error('penerbit') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Tahun Terbit</label>
                            <input type="number" class="form-control rounded-3" wire:model="tahun" placeholder="2024">
                            @error('tahun') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Jumlah Stok</label>
                            <input type="number" class="form-control rounded-3" wire:model="jumlah">
                            @error('jumlah') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 shadow-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Simpan Buku
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Informasi Buku</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $b)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                                            <i class="bi bi-book-half fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $b->judul }}</div>
                                            <div class="small text-muted">{{ $b->penulis }} | {{ $b->penerbit }} ({{ $b->tahun }})</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill">
                                        <i class="bi bi-tag-fill me-1"></i> {{ $b->kategori->nama ?? 'Umum' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $b->jumlah }}</div>
                                    <small class="text-muted">Ekslempar</small>
                                </td>
                                <td class="text-center">
                                    <button wire:click="delete({{ $b->id }})" 
                                            onclick="confirm('Yakin ingin menghapus buku ini?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://illustrations.popsy.co/white/reading-a-book.svg" alt="Empty" style="width: 150px;" class="mb-3">
                                    <p class="text-muted">Belum ada koleksi buku yang terdaftar.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>