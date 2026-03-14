<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Data Siswa</h2>
            <p class="text-muted small">Manajemen anggota perpustakaan (Siswa)</p>
        </div>
        <input wire:model.live="search" type="text" class="form-control w-25 rounded-pill shadow-sm border-0 px-4" placeholder="Cari nama siswa...">
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('message') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-4">
                    <i class="bi bi-person-plus text-warning me-2"></i>
                    {{ $isEdit ? 'Edit Data Siswa' : 'Registrasi Siswa' }}
                </h5>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted">NAMA LENGKAP</label>
                    <input wire:model="name" type="text" class="form-control bg-light border-0 py-2">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted">EMAIL (USER ID)</label>
                    <input wire:model="email" type="email" class="form-control bg-light border-0 py-2">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="small fw-bold text-muted">KELAS</label>
                        <select wire:model="kelas" class="form-select bg-light border-0 py-2">
                            <option value="">Pilih</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="small fw-bold text-muted">JURUSAN</label>
                        <select wire:model="jurusan" class="form-select bg-light border-0 py-2">
                            <option value="">Pilih</option>
                            <option value="TO">TO</option>
                            <option value="TPFL">TPFL</option>
                            <option value="PPLG">PPLG</option>
                            <option value="ANIMASI">ANIMASI</option>
                            <option value="BCF">BCF</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold text-muted">PASSWORD {{ $isEdit ? '(Kosongkan jika tak diubah)' : '' }}</label>
                    <input wire:model="password" type="password" class="form-control bg-light border-0 py-2">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <button wire:click="{{ $isEdit ? 'update' : 'store' }}" class="btn btn-warning w-100 py-2 fw-bold text-white shadow-sm rounded-3">
                    {{ $isEdit ? 'SIMPAN PERUBAHAN' : 'DAFTARKAN SISWA' }}
                </button>
                @if($isEdit)
                    <button wire:click="resetInput" class="btn btn-link w-100 text-muted mt-1 text-decoration-none small">Batal</button>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted fw-bold">
                            <th class="ps-4 py-3">SISWA</th>
                            <th>KELAS & JURUSAN</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $siswa)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        {{ substr($siswa->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $siswa->name }}</div>
                                        <div class="text-muted small">{{ $siswa->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-3">
                                    {{ $siswa->kelas }} {{ $siswa->jurusan }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button wire:click="edit({{ $siswa->id }})" class="btn btn-sm btn-light text-primary border me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button onclick="confirm('Hapus siswa ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $siswa->id }})" class="btn btn-sm btn-light text-danger border">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted italic">Belum ada data siswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-top">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
</div>