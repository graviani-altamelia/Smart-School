<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Kategori Buku</h2>
            <p class="text-muted">Kelola kategori untuk pengelompokan buku</p>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success py-2 px-4 shadow-sm rounded-pill mb-0">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 p-4 rounded-4">
                <h5 class="fw-bold mb-3">Tambah Kategori</h5>
                <form wire:submit.prevent="store">
                    <div class="mb-3">
                        <label class="small fw-bold">Nama Kategori</label>
                        <input type="text" class="form-control" wire:model="nama" placeholder="Contoh: Sains, Novel, Sejarah">
                        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Deskripsi (Opsional)</label>
                        <textarea class="form-control" wire:model="deskripsi" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2">
                        <i class="bi bi-tag-fill me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $cat->nama }}</td>
                            <td class="text-muted small">{{ $cat->deskripsi ?? '-' }}</td>
                            <td class="text-center">
                                <button wire:click="delete({{ $cat->id }})" 
                                        onclick="confirm('Hapus kategori ini?') || event.stopImmediatePropagation()"
                                        class="btn btn-sm btn-outline-danger border-0">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>