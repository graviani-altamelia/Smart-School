<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/login.png') }}">
    <title>Smart School - 2026</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { 
            --sidebar-bg: #1a1d21; 
            --primary-color: #0d6efd; 
            --sidebar-width: 260px;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f4f7fa; 
            margin: 0;
            overflow-x: hidden;
        }
        
        .sidebar { 
            width: var(--sidebar-width); 
            background: var(--sidebar-bg); 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
        }

        .sidebar .nav-link { 
            color: #8a949d; 
            padding: 12px 20px; 
            font-weight: 500; 
            transition: 0.3s; 
            text-decoration: none; 
            display: flex;
            align-items: center;
            border-radius: 10px;
            margin: 2px 15px;
        }

        .sidebar .nav-link.active { 
            color: #fff; 
            background: var(--primary-color); 
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .sidebar .nav-link:hover:not(.active) { 
            color: #fff; 
            background: rgba(255, 255, 255, 0.08); 
        }

        .main-content { 
            margin-left: var(--sidebar-width); 
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            padding: 1.5rem;
        } 

        .auth-wrapper { 
            width: 100%;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: #f8f9fa;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>

    @livewireStyles
</head>
<body>

    <div class="d-flex">
        @auth
            <nav class="sidebar shadow">
                <div class="p-4 mb-2 text-center">
                    <h4 class="text-white fw-bold mb-0">Smart <span class="text-primary">School</span></h4>
                    <div class="mt-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small">
                            <i class="bi bi-person-badge me-1"></i> {{ strtoupper(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="nav flex-column flex-grow-1">
                    {{-- MENU ADMIN --}}
                    @if(auth()->user()->role == 'admin')
                        <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px;">Menu Administrator</small>
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
                            <i class="bi bi-journal-text me-2"></i> Kelola Peminjaman
                        </a>
                        <a href="{{ route('admin.pengembalian') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.pengembalian') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check me-2"></i> Kelola Pengembalian
                        </a>
                        <a href="{{ route('admin.log') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.log') ? 'active' : '' }}">
                            <i class="bi bi-clock-history me-2"></i> Aktivitas Log
                        </a>
                    @endif

                    {{-- MENU PETUGAS --}}
                    @if(auth()->user()->role == 'petugas')
                        <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px;">Menu Petugas</small>
                        <a href="{{ route('petugas.dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-shield-check me-2"></i> Validasi Pinjam
                        </a>
                        {{-- PERBAIKAN: Mengarah ke petugas.peminjaman --}}
                        <a href="{{ route('petugas.peminjaman') }}" wire:navigate class="nav-link {{ request()->routeIs('petugas.peminjaman') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill me-2"></i> Peminjaman Aktif
                        </a>
                        <a href="{{ route('petugas.pengembalian') }}" wire:navigate class="nav-link {{ request()->routeIs('petugas.pengembalian') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check-fill me-2"></i> Riwayat Kembali
                        </a>
                    @endif

                    {{-- MENU SISWA --}}
                    @if(auth()->user()->role == 'siswa')
                        <small class="text-uppercase text-muted fw-bold px-4 mt-3 mb-2" style="font-size: 10px; letter-spacing: 1px;">Menu Siswa</small>
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
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">@csrf</form>
                    <button onclick="event.preventDefault(); if(confirm('Yakin ingin keluar?')) document.getElementById('logout-form').submit();" 
                            class="btn btn-link nav-link text-danger border-0 text-start w-100 py-3">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </button>
                </div>
            </nav>

            <main class="main-content">
                {{ $slot }}
            </main>
        @endauth

        @guest
            <div class="auth-wrapper">
                {{ $slot }}
            </div>
        @endguest
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>