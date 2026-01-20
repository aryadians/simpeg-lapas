<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIMPEG Lapas' }}</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: { 600: '#4f46e5', 100: '#e0e7ff' },
                        purple: { 600: '#9333ea' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 antialiased font-sans">

    {{-- NAVBAR (MENU ATAS) --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    {{-- Logo Aplikasi --}}
                    <div class="shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                            S
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-gray-800">
                            SIMPEG <span class="text-indigo-600">Lapas</span>
                        </span>
                    </div>

                    {{-- Link Menu Navigasi --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        
                        {{-- Menu Dashboard --}}
                        <a href="/" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out 
                           {{ request()->is('/') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           📅 Jadwal Dinas
                        </a>

                        {{-- Menu Pegawai --}}
                        <a href="/pegawai" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out 
                           {{ request()->is('pegawai*') ? 'border-indigo-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           👥 Data Pegawai
                        </a>

                    </div>
                </div>
                
                {{-- User Info & Logout (Dinamis sesuai Login) --}}
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->jabatan ?? 'Petugas' }}</div>
                    </div>
                    
                    {{-- Tombol Logout --}}
                    <a href="/logout" class="h-10 w-10 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition shadow-sm" title="Keluar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Script Global SweetAlert (Notifikasi) --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('roster-updated', (event) => {
                // Cek apakah event mengirim pesan spesifik atau default
                let message = event.message || (typeof event === 'string' ? event : 'Data berhasil disimpan!');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    </script>
</body>
</html>