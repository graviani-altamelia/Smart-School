<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('assets/login.png') }}">
    
    {{-- Dinamis Title --}}
    <title>{{ $title ?? 'Smart School' }}</title>
    
    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { 
            --primary-orange: #C05621; 
            --sidebar-dark: #2D1A12; 
            --bg-soft: #FDF8F5;
            --sidebar-width: 280px;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-soft); 
            margin: 0;
            overflow-x: hidden;
        }
        
        .sidebar { 
            width: var(--sidebar-width); 
            background: var(--sidebar-dark); 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0;
            z-index: 1050; /* Di atas elemen lain */
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar .nav-link { 
            color: #A39288; 
            padding: 12px 20px; 
            font-weight: 500; 
            transition: 0.2s; 
            text-decoration: none; 
            display: flex;
            align-items: center;
            border-radius: 12px;
            margin: 4px 15px;
        }

        .sidebar .nav-link.active { 
            color: #fff; 
            background: var(--primary-orange); 
            box-shadow: 0 4px 15px rgba(192, 86, 33, 0.4);
        }

        .sidebar .nav-link:hover:not(.active) { 
            color: #fff; 
            background: rgba(255, 255, 255, 0.08); 
            transform: translateX(5px);
        }

        .main-content { 
            margin-left: var(--sidebar-width); 
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            padding: 2rem;
            transition: all 0.3s;
        } 

        .auth-wrapper { 
            width: 100%;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: var(--bg-soft);
        }

        /* Overlay untuk Mobile saat sidebar buka */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .sidebar.show + .sidebar-overlay { display: block; }
            .main-content { margin-left: 0; width: 100%; padding: 1rem; }
            .auth-wrapper { padding: 10px; }
        }
    </style>

    @livewireStyles
</head>
<body>

    @auth
        {{-- Overlay klik untuk tutup sidebar di mobile --}}
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <nav class="sidebar shadow" id="mainSidebar">
            <div class="p-4 mb-2 text-center">
                <div class="mb-3">
                    <img src="{{ asset('assets/login.png') }}" width="60" alt="Logo">
                </div>
                <h4 class="text-white fw-bold mb-0">Smart <span style="color: var(--primary-orange)">School</span></h4>
                <div class="mt-2">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill small">
                        <i class="bi bi-person-badge me-1"></i> {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
            </div>
            
            <div class="nav flex-column flex-grow-1">
                @if(auth()->user()->role == 'admin')
                    <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px; opacity: 0.6;">Menu Administrator</small>
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.siswa') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.siswa') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Kelola User
                    </a> 
                    <a href="{{ route('admin.buku') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.buku') ? 'active' : '' }}">
                        <i class="bi bi-book-half me-2"></i> Kelola Buku
                    </a>
                    <a href="{{ route('admin.pinjam') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.pinjam') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Peminjaman
                    </a>
                    <a href="{{ route('admin.log') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.log') ? 'active' : '' }}">
                        <i class="bi bi-clock-history me-2"></i> Aktivitas Log
                    </a>
                @endif

                @if(auth()->user()->role == 'petugas')
                    <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px; opacity: 0.6;">Menu Petugas</small>
                    <a href="{{ route('petugas.dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-shield-check me-2"></i> Validasi Pinjam
                    </a>
                    <a href="{{ route('petugas.peminjaman') }}" wire:navigate class="nav-link {{ request()->routeIs('petugas.peminjaman') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill me-2"></i> Peminjaman Aktif
                    </a>
                @endif

                @if(auth()->user()->role == 'siswa')
                    <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px; opacity: 0.6;">Menu Siswa</small>
                    <a href="{{ route('home') }}" wire:navigate class="nav-link {{ request()->routeIs('home','siswa.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('siswa.daftar-buku') }}" wire:navigate class="nav-link {{ request()->routeIs('siswa.daftar-buku') ? 'active' : '' }}">
                        <i class="bi bi-search me-2"></i> Cari Buku
                    </a>
                    <a href="{{ route('siswa.riwayat-pinjam') }}" wire:navigate class="nav-link {{ request()->routeIs('siswa.riwayat-pinjam') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Riwayat Saya
                    </a>
                @endif
            </div>

            <div class="mt-auto p-3">
                <hr class="bg-secondary opacity-25 mb-3">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">@csrf</form>
                <button type="button" onclick="confirmLogout()" class="btn btn-link nav-link text-danger border-0 text-start w-100 py-3">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
            </div>
        </nav>

        <main class="main-content">
            {{-- Navbar Mobile --}}
            <div class="d-md-none d-flex align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
                <button class="btn btn-warning text-white me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="fw-bold mb-0">Smart <span class="text-orange">School</span></h5>
            </div>
            
            {{ $slot }}
        </main>
    @endauth

    @guest
        <div class="auth-wrapper">
            {{ $slot }}
        </div>
    @endguest

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            sidebar.classList.toggle('show');
        }

        function confirmLogout() {
            if(confirm('Yakin ingin keluar?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // Menutup sidebar otomatis saat link di klik (untuk mobile)
        document.addEventListener('livewire:navigated', () => {
            document.getElementById('mainSidebar')?.classList.remove('show');
        });
    </script>
    @livewireScripts
</body>
</html>