<div>
    <div class="d-flex justify-content-between align-items-center mb-4 p-2">
        <div>
            <h2 class="fw-bold mb-0 text-[#C05621]">Kategori Buku</h2>
            <p class="text-muted">Kelola kategori untuk pengelompokan buku</p>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-warning py-2 px-4 shadow-sm rounded-pill mb-0 border-0">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 p-4 rounded-4 shadow-orange-100">
                <h5 class="fw-bold mb-3 text-dark">Tambah Kategori</h5>
                <form wire:submit.prevent="store">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">NAMA KATEGORI</label>
                        <input type="text" class="form-control rounded-3 border-orange-100 shadow-sm focus:ring-orange-500" wire:model="nama" placeholder="Contoh: Sains, Novel, Sejarah">
                        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">DESKRIPSI (OPSIONAL)</label>
                        <textarea class="form-control rounded-3 border-orange-100 shadow-sm" wire:model="deskripsi" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 rounded-3 py-2 text-white fw-bold shadow-sm">
                        <i class="bi bi-tag-fill me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden shadow-orange-100">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-orange-50">
                        <tr>
                            <th class="ps-4 text-orange-800 py-3 small fw-bold">NAMA KATEGORI</th>
                            <th class="text-orange-800 py-3 small fw-bold">DESKRIPSI</th>
                            <th class="text-center text-orange-800 py-3 small fw-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">{{ $cat->nama_kategori }}</span>
                            </td>
                            <td class="text-muted small">{{ $cat->deskripsi ?? '-' }}</td>
                            <td class="text-center">
                                <button wire:click="delete({{ $cat->id }})" 
                                        onclick="confirm('Yakin ingin menghapus kategori ini?') || event.stopImmediatePropagation()"
                                        class="btn btn-sm btn-light text-danger rounded-3 shadow-sm">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted italic">
                                <i class="bi bi-folder-x d-block fs-2 mb-2 opacity-25"></i>
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>