<div>
    <x-slot:title>Daftar - Smart School</x-slot:title>

    <div class="auth-container">
        <div class="auth-card shadow-lg">
            {{-- Sisi Kiri: Branding (Banner) --}}
            <div class="auth-banner d-none d-lg-flex">
                <div class="p-5 text-white w-100">
                    <div class="mb-5">
                        <img src="{{ asset('assets/login.png') }}" width="80" class="mb-3">
                        <h2 class="fw-bold display-6 mb-0">Smart <span style="color: #FFD099;">School</span></h2>
                        <div class="bg-warning mt-2" style="height: 4px; width: 60px; border-radius: 10px;"></div>
                    </div>
                    
                    <h4 class="fw-bold mb-3">Gabung Sekarang!</h4>
                    <p class="opacity-75 mb-5">Jelajahi ribuan literatur digital tanpa batas ruang dan waktu.</p>

                    <div class="feature-list">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box me-3"><i class="bi bi-book"></i></div>
                            <span>Koleksi E-Book Terlengkap</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3"><i class="bi bi-person-check"></i></div>
                            <span>Pendaftaran Siswa Otomatis</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Form (Proporsional) --}}
            <div class="auth-form-side">
                <div class="p-4 p-md-5 w-100">
                    <div class="mb-4">
                        <h3 class="fw-bold text-dark mb-1">Daftar Akun</h3>
                        <p class="text-muted small">Lengkapi data Anda untuk mulai meminjam buku.</p>
                    </div>

                    <form wire:submit.prevent="register">
                        {{-- Baris 1: Nama --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">NAMA LENGKAP</label>
                            <div class="input-group">
                                <span class="input-group-text custom-addon"><i class="bi bi-person"></i></span>
                                <input type="text" wire:model.blur="name" class="form-control custom-input @error('name') is-invalid @enderror" placeholder="Nama lengkap">
                            </div>
                            @error('name') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        {{-- Baris 2: Email --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">EMAIL SEKOLAH</label>
                            <div class="input-group">
                                <span class="input-group-text custom-addon"><i class="bi bi-envelope"></i></span>
                                <input type="email" wire:model.blur="email" class="form-control custom-input @error('email') is-invalid @enderror" placeholder="nama@sekolah.sch.id">
                            </div>
                            @error('email') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        {{-- Baris 3: Kelas & Jurusan (Sejajar horizontal untuk hemat ruang vertikal) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-muted mb-1">KELAS</label>
                                <select wire:model.blur="kelas" class="form-select custom-input @error('kelas') is-invalid @enderror">
                                    <option value="">Pilih</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-muted mb-1">JURUSAN</label>
                                <select wire:model.blur="jurusan" class="form-select custom-input @error('jurusan') is-invalid @enderror">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="TO">TO</option>
                                    <option value="TPFL">TPFL</option>
                                    <option value="PPLG">PPLG</option>
                                    <option value="ANIMASI">ANIMASI</option>
                                    <option value="BCF">BCF</option>
                                </select>
                            </div>
                        </div>

                        {{-- Baris 4: Password --}}
                        <div class="mb-4" x-data="{ show: false }">
                            <label class="form-label small fw-bold text-muted mb-1">KATA SANDI</label>
                            <div class="input-group">
                                <span class="input-group-text custom-addon"><i class="bi bi-lock"></i></span>
                                <input :type="show ? 'text' : 'password'" wire:model.blur="password" class="form-control custom-input border-end-0 @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                                <span class="input-group-text bg-white border-start-0 custom-addon-eye" @click="show = !show" style="cursor: pointer;">
                                    <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                                </span>
                            </div>
                            @error('password') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-orange">
                            DAFTAR AKUN SEKARANG
                        </button>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Sudah punya akun? 
                                <a href="{{ route('login') }}" wire:navigate class="text-decoration-none fw-bold" style="color: #C85B28;">Masuk Disini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root { --primary-orange: #C05621; --sidebar-dark: #2D1A12; --bg-soft: #FDF8F5; }
        
        /* Container utama tetap satu layar penuh */
        .auth-container { min-height: 100vh; height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-soft); padding: 15px; overflow: hidden; }
        
        /* Card menyesuaikan dengan layar tanpa kekecilan */
        .auth-card { background: #fff; border-radius: 25px; display: flex; width: 100%; max-width: 1050px; height: auto; max-height: 95vh; overflow: hidden; border: none; }
        
        .auth-banner { background: var(--sidebar-dark); width: 45%; flex-direction: column; justify-content: center; background-image: linear-gradient(135deg, #2D1A12 0%, #442a1e 100%); }
        
        .auth-form-side { width: 55%; background: #fff; display: flex; align-items: center; overflow-y: auto; }
        
        .icon-box { width: 40px; height: 40px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #FFD099; font-size: 1.2rem; }

        /* Input ukuran standar agar tidak kekecilan */
        .custom-input { border: 1.5px solid #eee !important; border-radius: 10px !important; padding: 10px 15px !important; font-size: 0.95rem; }
        .custom-input:focus { border-color: var(--primary-orange) !important; box-shadow: 0 0 0 4px rgba(192, 86, 33, 0.1) !important; }
        
        .custom-addon { background: #fdfdfd; border: 1.5px solid #eee; border-right: none; border-radius: 10px 0 0 10px !important; color: #999; }
        .input-group > .custom-input { border-radius: 0 10px 10px 0 !important; }
        
        .custom-addon-eye { border: 1.5px solid #eee; border-left: none; border-radius: 0 10px 10px 0 !important; color: #999; }
        .input-group > .custom-input.border-end-0 { border-radius: 0 !important; }
        
        .btn-primary { background-color: var(--primary-orange); border: none; transition: 0.3s; padding: 12px !important; }
        .btn-primary:hover { background-color: #a0471b; transform: translateY(-2px); }
        .shadow-orange { box-shadow: 0 8px 15px rgba(192, 86, 33, 0.2); }

        /* Scrollbar halus jika layar sangat kecil (misal laptop 12 inch) */
        .auth-form-side::-webkit-scrollbar { width: 5px; }
        .auth-form-side::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }

        @media (max-width: 991px) { 
            .auth-banner { display: none !important; } 
            .auth-form-side { width: 100%; } 
            .auth-card { max-width: 450px; max-height: 98vh; }
            .auth-container { overflow-y: auto; height: auto; min-height: 100vh; }
        }
    </style>
</div>