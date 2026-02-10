<div class="card border-0 shadow-lg rounded-4 p-4" style="width: 100%; max-width: 450px;">
    <div class="text-center mb-3">
        <div class="mb-2">
            <img src="{{ asset('assets/login.png') }}" alt="Logo Smart School" class="img-fluid" style="max-height: 80px;">        
        </div>
        <h4 class="fw-bold mb-0">{{ $isRegister ? 'Daftar Akun Siswa' : 'Selamat Datang' }}</h4>
        <p class="text-muted small">Smart School Library System</p>
    </div>

    @if($isRegister)
        <form wire:submit.prevent="register" wire:key="form-register">
            <div class="mb-2" wire:key="reg-name-wrapper">
                <label class="form-label mb-1 small fw-bold">Nama Lengkap</label>
                <input type="text" wire:model="name" class="form-control form-control-sm rounded-3" placeholder="Nama lengkap..." autocomplete="off">
                @error('name') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2" wire:key="reg-email-wrapper">
                <label class="form-label mb-1 small fw-bold">Email Sekolah</label>
                <input type="email" wire:model="email" class="form-control form-control-sm rounded-3" placeholder="email@sekolah.com" autocomplete="off">
                @error('email') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
            </div>

            <div class="row g-2 mb-2" wire:key="reg-details-row">
                <div class="col-6">
                    <label class="form-label mb-1 small fw-bold">Kelas</label>
                    <select wire:model="kelas" class="form-select form-select-sm rounded-3">
                        <option value="">Pilih</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label mb-1 small fw-bold">Jurusan</label>
                    <select wire:model="jurusan" class="form-select form-select-sm rounded-3">
                        <option value="">Pilih</option>
                        <option value="TO">TO</option>
                        <option value="TPFL">TPFL</option>
                        <option value="PPLG">PPLG</option>
                        <option value="ANIMASI">ANIMASI</option>
                        <option value="BCF">BCF</option>
                    </select>
                </div>
                <div class="col-12 text-center">
                    @error('kelas') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                    @error('jurusan') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3" x-data="{ show: false }" wire:key="reg-password-wrapper">
                <label class="form-label mb-1 small fw-bold">Password</label>
                <div class="input-group input-group-sm">
                    <input :type="show ? 'text' : 'password'" wire:model="password" class="form-control rounded-start-3" placeholder="******">
                    <button class="btn btn-outline-secondary rounded-end-3" type="button" @click="show = !show">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                @error('password') <span class="text-danger" style="font-size: 10px;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 py-2 fw-bold shadow-sm">Daftar</button>
            <p class="text-center mt-2 mb-0 small">Sudah punya akun? <a href="#" wire:click.prevent="toggleRegister" class="text-decoration-none fw-bold">Login</a></p>
        </form>
    @else
        <form wire:submit.prevent="login" wire:key="form-login">
            <div class="mb-3" wire:key="login-email-wrapper">
                <label class="form-label small fw-bold">Email Sekolah</label>
                <input type="email" wire:model="email" class="form-control rounded-3" placeholder="Masukkan email..." autocomplete="off">
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3" x-data="{ show: false }" wire:key="login-password-wrapper">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <input :type="show ? 'text' : 'password'" wire:model="password" class="form-control rounded-start-3" placeholder="Masukkan password...">
                    <button class="btn btn-outline-secondary rounded-end-3" type="button" @click="show = !show">
                        <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold shadow-sm">Masuk</button>
            <p class="text-center mt-3 mb-0 small">Siswa baru? <a href="#" wire:click.prevent="toggleRegister" class="text-decoration-none fw-bold">Daftar</a></p>
        </form>
    @endif
</div>