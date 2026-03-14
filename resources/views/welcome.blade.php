<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Smart School - Perpustakaan Digital</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'brand-primary': '#C85B28', 
                            'brand-dark': '#3B2218',    
                            'brand-accent': '#FFD099',  
                            'brand-bg-light': '#FDFDFC',
                        }
                    }
                }
            }
        </script>
        
        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            .blob {
                border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
                background: linear-gradient(135deg, #C85B28 0%, #3B2218 100%);
                animation: morph 8s ease-in-out infinite;
            }
            @keyframes morph {
                0% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; }
                50% { border-radius: 70% 30% 46% 54% / 30% 29% 71% 70%; }
                100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; }
            }
        </style>
    </head>
    <body class="bg-brand-bg-light text-slate-900 antialiased">
        
        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-orange-100">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-brand-dark rounded-lg flex items-center justify-center text-white font-bold text-xl">S</div>
                    <span class="text-xl font-bold tracking-tight text-brand-dark">Smart <span class="text-brand-primary">School</span></span>
                </div>
                <div class="flex gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-brand-dark hover:text-brand-primary py-2 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-primary py-2 transition">Masuk</a>
                        {{-- SEKARANG MENGARAH KE REGISTER --}}
                        <a href="{{ route('register') }}" class="bg-brand-primary text-white px-5 py-2 rounded-full text-sm font-semibold hover:brightness-110 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="pt-40 pb-20 px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div>
                    <span class="inline-block px-4 py-1.5 mb-6 text-sm font-medium text-brand-primary bg-orange-50 rounded-full border border-orange-100">
                        ✨ Perpustakaan Digital UKK 2026
                    </span>
                    <h1 class="text-6xl lg:text-7xl font-bold leading-tight mb-6 text-brand-dark tracking-tight">
                        Cerdas Mengelola <br>
                        <span class="text-brand-primary">Ilmu Pengetahuan.</span>
                    </h1>
                    <p class="text-xl text-slate-600 mb-8 max-w-lg leading-relaxed">
                        Satu platform terintegrasi untuk akses koleksi buku, peminjaman mandiri, dan monitoring denda tanpa ribet.
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        {{-- TOMBOL UTAMA KE LOGIN --}}
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-brand-dark text-white rounded-2xl font-bold text-lg hover:bg-black transition shadow-xl">
                            Mulai Sekarang
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center items-center">
                    <div class="blob w-72 h-72 lg:w-96 lg:h-96 opacity-20 absolute"></div>
                    
                    <div class="relative w-full space-y-4">
                        <div class="bg-white p-6 rounded-2xl shadow-xl border border-orange-50 flex items-center gap-4 transform translate-x-10 hover:-translate-y-1 transition duration-500">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-brand-primary font-bold">01</div>
                            <div>
                                <h4 class="font-bold text-brand-dark">Akses 24/7</h4>
                                <p class="text-sm text-slate-500">Baca katalog buku kapan saja.</p>
                            </div>
                        </div>
                        
                        <div class="bg-brand-primary p-6 rounded-2xl shadow-2xl text-white flex items-center gap-4 z-10 relative hover:-translate-y-1 transition duration-500">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center font-bold">02</div>
                            <div>
                                <h4 class="font-bold">Self-Service</h4>
                                <p class="text-sm text-white/80">Pinjam buku lewat satu klik.</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-xl border border-orange-50 flex items-center gap-4 transform translate-x-12 hover:-translate-y-1 transition duration-500">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-brand-primary font-bold">03</div>
                            <div>
                                <h4 class="font-bold text-brand-dark">Notifikasi Real-time</h4>
                                <p class="text-sm text-slate-500">Info pengembalian & denda otomatis.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-12 text-center text-sm text-slate-500 mt-20">
            <div class="max-w-xs mx-auto border-t border-slate-100 pt-8">
                <p class="font-medium text-brand-dark">&copy; 2026 Smart School Perpustakaan.</p>
                <p>UKK PPLG Project</p>
            </div>
        </footer>

    </body>
</html>