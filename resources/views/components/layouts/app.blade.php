<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIMPEG Lapas' }}</title>
    
    {{-- CDN Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    {{-- Custom Styles & Animations --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Animation Keyframes */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-pop-in { animation: popIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.5); }
            70% { opacity: 1; transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: { 600: '#4f46e5', 50: '#eef2ff' },
                        purple: { 600: '#9333ea' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 antialiased text-gray-800">

    {{-- ========================================= --}}
    {{-- NAVBAR BARU (FIX RAPI & SINGLE LINE)      --}}
    {{-- ========================================= --}}
        <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                {{-- KIRI: Logo & Menu --}}
                <div class="flex items-center gap-8 overflow-x-auto no-scrollbar">
                    
                    {{-- Logo --}}
                    <div class="shrink-0 flex items-center gap-3">
                        <div class="h-10 w-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
                            S
                        </div>
                        <span class="text-2xl font-extrabold text-gray-800 tracking-tight hidden md:block">
                            SIMPEG <span class="text-indigo-600">Lapas</span>
                        </span>
                    </div>

                    {{-- Menu Navigasi (Desktop) --}}
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="/" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->is('/') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">🗓️</span>
                            <span class="whitespace-nowrap">Jadwal</span>
                        </a>
                        <a href="/pegawai" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->is('pegawai*') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
                            <span class="whitespace-nowrap">Pegawai</span>
                        </a>
                        <a href="/inventaris" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->is('inventaris*') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">📦</span>
                            <span class="whitespace-nowrap">Inventaris</span>
                        </a>
                        <a href="/cuti" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->is('cuti*') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">🏖️</span>
                            <span class="whitespace-nowrap">E-Cuti</span>
                        </a>
                        <a href="/laporan" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('laporan') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">📋</span>
                            <span class="whitespace-nowrap">Laporan</span>
                        </a>
                        <a href="{{ route('incident-reports') }}" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('incident-reports') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">📝</span>
                            <span class="whitespace-nowrap">Laporan Kejadian</span>
                        </a>
                        <a href="/rekap-absensi" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('rekap') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">📊</span>
                            <span class="whitespace-nowrap">Rekap</span>
                        </a>
                        @if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')
                        <a href="{{ route('tukin.report') }}" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('tukin.*') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">💰</span>
                            <span class="whitespace-nowrap">Tukin</span>
                        </a>
                        <a href="{{ route('post.assignment') }}" wire:navigate class="group flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold transition-all {{ request()->routeIs('post.assignment') ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <span class="text-lg group-hover:scale-110 transition-transform">📍</span>
                            <span class="whitespace-nowrap">Plotting Pos</span>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- KANAN: User Profile & Logout + Hamburger --}}
                <div class="flex items-center gap-2">
                    <div class="hidden md:flex items-center gap-4">
                        {{-- Info User --}}
                        <div class="flex flex-col items-end mr-2">
                            <span class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'Guest' }}</span>
                            <span class="text-[10px] uppercase tracking-wider text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                                {{ auth()->user()->role ?? '-' }}
                            </span>
                        </div>
                        {{-- Tombol Logout --}}
                        <a href="/logout" class="h-10 w-10 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm border border-red-100 group" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>
                    
                    {{-- Tombol Hamburger (Mobile) --}}
                    <div class="lg:hidden">
                        <button @click="open = !open" class="h-12 w-12 rounded-xl bg-white hover:bg-indigo-50 text-indigo-600 border-2 border-indigo-100 shadow-sm transition-all duration-300 transform hover:scale-110 active:scale-95 flex items-center justify-center">
                            <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            <svg x-show="open" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Backdrop --}}
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden">
        </div>

        {{-- Mobile Menu Panel --}}
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition-transform ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-transform ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             x-cloak
             class="fixed top-0 right-0 bottom-0 z-50 w-full max-w-sm bg-white shadow-2xl p-6 lg:hidden flex flex-col">
            
            {{-- Header Menu Mobile --}}
            <div class="flex justify-between items-center pb-6 border-b">
                <span class="text-xl font-extrabold text-gray-800">Menu</span>
                <button @click="open = false" class="h-10 w-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-500 transition-all">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Nav Links --}}
            <div class="mt-6 flex-grow space-y-2">
                <a @click="open = false" href="/" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->is('/') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">🗓️ <span class="flex-1">Jadwal</span></a>
                <a @click="open = false" href="/pegawai" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->is('pegawai*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">👥 <span class="flex-1">Pegawai</span></a>
                <a @click="open = false" href="/inventaris" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->is('inventaris*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">📦 <span class="flex-1">Inventaris</span></a>
                <a @click="open = false" href="/cuti" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->is('cuti*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">🏖️ <span class="flex-1">E-Cuti</span></a>
                <a @click="open = false" href="/laporan" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->routeIs('laporan') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">📋 <span class="flex-1">Laporan</span></a>
                <a @click="open = false" href="{{ route('incident-reports') }}" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->routeIs('incident-reports') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">📝 <span class="flex-1">Laporan Kejadian</span></a>
                <a @click="open = false" href="/rekap-absensi" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->routeIs('rekap') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">📊 <span class="flex-1">Rekap</span></a>
                @if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')
                    <a @click="open = false" href="{{ route('tukin.report') }}" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->routeIs('tukin.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">💰 <span class="flex-1">Tukin</span></a>
                    <a @click="open = false" href="{{ route('post.assignment') }}" wire:navigate class="flex items-center gap-4 px-4 py-3 rounded-xl text-lg font-bold {{ request()->routeIs('post.assignment') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-indigo-600' }}">📍 <span class="flex-1">Plotting Pos</span></a>
                @endif
            </div>

            {{-- Footer Menu Mobile --}}
            <div class="mt-6 pt-6 border-t">
                <a href="/logout" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl text-lg font-bold bg-red-50 text-red-600 hover:bg-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    {{-- ========================================= --}}
    {{-- LOGIKA SWEETALERT (TOAST & CONFIRM)       --}}
    {{-- ========================================= --}}
    <script>
        document.addEventListener('livewire:init', () => {
            
            // 1. Toast Notification (Pojok Kanan Atas)
            Livewire.on('flash-message', (event) => {
                // Handle format data (array vs object)
                const data = Array.isArray(event) ? event[0] : event;
                const { type = 'success', title = 'Berhasil!', text = '' } = data;

                Swal.fire({
                    icon: type,
                    title: title,
                    text: text,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    background: '#fff',
                    customClass: {
                        popup: 'shadow-xl rounded-xl border border-gray-100 p-3'
                    },
                    showClass: { popup: 'animate__animated animate__fadeInRight' },
                    hideClass: { popup: 'animate__animated animate__fadeOutRight' }
                });
            });

            // 2. Confirm Dialog (Tengah Layar)
            Livewire.on('confirm-dialog', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                const { 
                    title = 'Anda Yakin?', 
                    text = 'Tindakan ini tidak dapat dibatalkan!', 
                    confirm_event, 
                    confirm_params 
                } = data;
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5', // Indigo 600
                    cancelButtonColor: '#ef4444',  // Red 500
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-4 py-2',
                        cancelButton: 'rounded-xl px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch(confirm_event, confirm_params);
                    }
                });
            });

            // 3. Listener untuk Event 'roster-updated' (Supaya konsisten dengan controller sebelumnya)
            Livewire.on('roster-updated', (event) => {
                 const data = Array.isArray(event) ? event[0] : event;
                 Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: data.message || 'Data berhasil diperbarui',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>