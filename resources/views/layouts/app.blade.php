<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-color: #f8f9fa; 
            margin: 0;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-width); 
            background: var(--sidebar-bg); 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .nav-link { 
            color: #8a949d; 
            padding: 12px 25px; 
            font-weight: 500; 
            transition: 0.3s; 
            text-decoration: none; 
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { 
            color: #fff; 
            background: rgba(13, 110, 253, 0.1); 
        }
        
        /* Main Content Adjustment */
        .main-content { 
            margin-left: var(--sidebar-width); 
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            padding: 2rem;
        } 

        /* Login Wrapper */
        .auth-wrapper { 
            width: 100%;
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Responsivitas */
        @media (max-width: 768px) {
            .sidebar { margin-left: -var(--sidebar-width); }
            .main-content { margin-left: 0; width: 100%; }
        }
    </style>

    @livewireStyles
</head>
<body>

    <div class="d-flex">
        @auth
            <nav class="sidebar shadow">
                <div class="p-4 mb-3 text-center">
                    <h4 class="text-white font-weight-bold mb-0">Smart <span class="text-primary">School</span></h4>
                    <span class="badge bg-primary mt-2">{{ strtoupper(auth()->user()->role) }}</span>
                    <hr class="bg-secondary opacity-25">
                </div>
                
                <div class="nav flex-column">
                    {{-- Role Admin & Petugas --}}
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'petugas')
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
                            <i class="bi bi-arrow-repeat me-2"></i> Peminjaman
                        </a>

                        <a href="{{ route('admin.pengembalian') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.pengembalian') ? 'active' : '' }}">
                            <i class="bi bi-calendar-x-fill me-2"></i> Pengembalian
                        </a>

                        <a href="{{ route('admin.log') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.log') ? 'active' : '' }}">
                            <i class="bi bi-clock-history me-2"></i> Aktivitas Log
                        </a>
                    @endif

                    {{-- Role Siswa --}}
                    @if(auth()->user()->role == 'siswa')
                        <a href="{{ route('home') }}" wire:navigate class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="bi bi-house-door me-2"></i> Home
                        </a>
                        {{-- SESUAIKAN: Gunakan route siswa.daftar-buku --}}
                        <a href="{{ route('siswa.daftar-buku') }}" wire:navigate class="nav-link {{ request()->routeIs('siswa.daftar-buku') ? 'active' : '' }}">
                            <i class="bi bi-search me-2"></i> Daftar Buku
                        </a>
                        <a href="{{ route('siswa.riwayat-pinjam') }}" wire:navigate class="nav-link {{ request()->routeIs('siswa.riwayat') ? 'active' : '' }}">
                            <i class="bi bi-journal-text me-2"></i> Riwayat Pinjam
                        </a>
                    @endif
                    
                    <hr class="bg-secondary mx-4 my-3 opacity-25">
                    
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">@csrf</form>
                    <button onclick="event.preventDefault(); if(confirm('Yakin ingin keluar?')) document.getElementById('logout-form').submit();" 
                            class="btn btn-link nav-link text-danger border-0 text-start w-100 ps-4">
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