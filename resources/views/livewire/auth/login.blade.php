<div>
    <x-slot:title>Masuk - Smart School</x-slot:title>

    <div class="auth-container">
        <div class="auth-card shadow-lg">
            {{-- Sisi Kiri: Branding --}}
            <div class="auth-banner d-none d-lg-flex">
                <div class="p-5 text-white w-100">
                    <div class="mb-5 text-center text-lg-start">
                        <img src="{{ asset('assets/login.png') }}" width="70" class="mb-3">
                        <h2 class="fw-bold display-6 mb-0">Smart <span style="color: #FFD099;">School</span></h2>
                        <div class="bg-warning mt-2" style="height: 4px; width: 60px; border-radius: 10px;"></div>
                    </div>
                    
                    <h4 class="fw-bold mb-3">Selamat Datang Kembali!</h4>
                    <p class="opacity-75 mb-5">Akses kembali ribuan koleksi buku digital, jurnal, dan literatur sekolah dalam satu genggaman.</p>

                    <div class="feature-list mt-auto">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box me-3"><i class="bi bi-journal-check"></i></div>
                            <span>Lanjutkan bacaan favoritmu hari ini.</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box me-3"><i class="bi bi-clock-history"></i></div>
                            <span>Cek denda & riwayat pinjaman real-time.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Form --}}
            <div class="auth-form-side">
                <div class="p-4 p-md-5">
                    <div class="mb-5">
                        <h3 class="fw-bold text-dark mb-1">Masuk Akun</h3>
                        <p class="text-muted small">Silakan masukkan email dan password sekolah Anda.</p>
                    </div>

                    <form wire:submit.prevent="login">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-2 text-uppercase">Email Sekolah</label>
                            <div class="input-group">
                                <span class="input-group-text custom-addon"><i class="bi bi-envelope"></i></span>
                                <input type="email" wire:model.blur="email" class="form-control custom-input @error('email') is-invalid @enderror" placeholder="nama@sekolah.sch.id">
                            </div>
                            @error('email') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4" x-data="{ show: false }">
                            <label class="form-label small fw-bold text-muted mb-2 text-uppercase">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text custom-addon"><i class="bi bi-lock"></i></span>
                                <input :type="show ? 'text' : 'password'" wire:model.blur="password" class="form-control custom-input border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••">
                                <span class="input-group-text bg-white border-start-0 custom-addon-eye" @click="show = !show" style="cursor: pointer;">
                                    <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                                </span>
                            </div>
                            @error('password') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-orange mb-4">
                            <span wire:loading.remove>MASUK SEKARANG <i class="bi bi-arrow-right ms-2"></i></span>
                            <span wire:loading>MENCOBA MASUK...</span>
                        </button>

                        <div class="text-center pt-2">
                            <p class="small text-muted mb-0">Belum punya akun? 
                                <a href="{{ route('register') }}" wire:navigate class="text-decoration-none fw-bold" style="color: #C85B28;">Daftar Siswa</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root { --primary-orange: #C05621; --sidebar-dark: #2D1A12; --bg-soft: #FDF8F5; }
        .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-soft); padding: 20px; }
        .auth-card { background: #fff; border-radius: 30px; display: flex; width: 100%; max-width: 1000px; overflow: hidden; border: none; }
        .auth-banner { background: var(--sidebar-dark); width: 45%; display: flex; flex-direction: column; background-image: linear-gradient(135deg, #2D1A12 0%, #442a1e 100%); }
        .auth-form-side { width: 55%; background: #fff; display: flex; align-items: center; }
        .icon-box { width: 35px; height: 35px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; border-radius: 10px; color: #FFD099; }
        .custom-input { border: 1.5px solid #eee !important; border-radius: 12px !important; padding: 12px 15px !important; font-size: 0.9rem; }
        .custom-input:focus { border-color: var(--primary-orange) !important; box-shadow: 0 0 0 4px rgba(192, 86, 33, 0.1) !important; }
        .custom-addon { background: #fdfdfd; border: 1.5px solid #eee; border-right: none; border-radius: 12px 0 0 12px !important; color: #999; }
        .input-group > .custom-input { border-radius: 0 12px 12px 0 !important; }
        .custom-addon-eye { border: 1.5px solid #eee; border-left: none; border-radius: 0 12px 12px 0 !important; color: #999; }
        .btn-primary { background-color: var(--primary-orange); border: none; transition: 0.3s; letter-spacing: 0.5px; }
        .btn-primary:hover { background-color: #a0471b; transform: translateY(-2px); }
        .shadow-orange { box-shadow: 0 10px 20px rgba(192, 86, 33, 0.2); }
        @media (max-width: 991px) { .auth-form-side { width: 100%; } .auth-card { max-width: 480px; } }
    </style>
</div>