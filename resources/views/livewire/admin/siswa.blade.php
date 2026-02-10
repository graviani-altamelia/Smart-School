<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Kelola Data Siswa</h2>
            <p class="text-muted">Manajemen Data Siswa</p>
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
                <h5 class="fw-bold mb-3">Tambah Siswa</h5>
                <form wire:submit.prevent="store">
                    <div class="mb-3">
                        <label class="small fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" wire:model="name" placeholder="Nama siswa...">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Kelas</label>
                            <select class="form-select" wire:model="kelas">
                                <option value="">Pilih</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            @error('kelas') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small fw-bold">Jurusan</label>
                            <select class="form-select" wire:model="jurusan">
                                <option value="">Pilih</option>
                                <option value="TO">TO</option>
                                <option value="TO">TPFL</option>
                                <option value="PPLG">PPLG</option>
                                <option value="ANIMASI">ANIMASI</option>
                                <option value="TO">BCF</option>
                            </select>
                            @error('jurusan') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold">Email</label>
                        <input type="email" class="form-control" wire:model="email" placeholder="email@gmail.com">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="small fw-bold">Password</label>
                        <input type="password" class="form-control" wire:model="password">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Simpan Data
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
                                <th class="ps-4 py-3">Siswa</th>
                                <th>Kelas/Jurusan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $m)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=random" class="rounded-circle me-3" width="35">
                                        <div>
                                            <div class="fw-bold">{{ $m->name }}</div>
                                            <div class="small text-muted">{{ $m->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $m->kelas }}</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $m->jurusan }}</span>
                                </td>
                                <td class="text-center">
                                    <button wire:click="delete({{ $m->id }})" 
                                            onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()"
                                            class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>