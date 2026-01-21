<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIMPEG Lapas' }}</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
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
        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            70% {
                opacity: 1;
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        .animate-pop-in {
            animation: popIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
    </style>
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
<body class="bg-gray-50 antialiased font-sans">

    {{-- NAVBAR (MENU ATAS) --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    {{-- Logo Aplikasi --}}
                    <div class="shrink-0 flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30">
                            S
                        </div>
                        <span class="font-black text-xl tracking-tight text-gray-800">
                            SIMPEG <span class="text-indigo-600">Lapas</span>
                        </span>
                    </div>

                    {{-- Link Menu Navigasi --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        
                        {{-- 1. Dashboard (Jadwal & Absen) --}}
                        <a href="/" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out 
                           {{ request()->is('/') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           📅 Jadwal & Absen
                        </a>

                        {{-- 2. Data Pegawai --}}
                        <a href="/pegawai" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out 
                           {{ request()->is('pegawai*') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           👥 Data Pegawai
                        </a>

                        {{-- 3. E-Cuti (Baru) --}}
                        <a href="/cuti" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out 
                           {{ request()->is('cuti*') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           🏖️ E-Cuti
                        </a>

                        {{-- 4. Laporan Aplusan (Baru) --}}
                        <a href="/laporan"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                           {{ request()->is('laporan*') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           📋 Laporan Jaga
                        </a>

                        {{-- 5. Rekap Absensi --}}
                        <a href="/rekap-absensi"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                           {{ request()->is('rekap*') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                           📊 Rekap Absen
                        </a>

                        {{-- 6. Laporan Tukin --}}
                        <a href="{{ route('tukin.report') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                            {{ request()->routeIs('tukin.report') ? 'border-indigo-600 text-indigo-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            💰 Laporan Tukin
                        </a>

                    </div>
                </div>
                
                {{-- User Info & Logout --}}
                <div class="flex items-center gap-4">
                    <a href="/profil" class="text-right hidden sm:block hover:bg-gray-50 p-2 rounded-lg transition group cursor-pointer" title="Edit Profil">
                        <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-500 font-mono">
                            {{ auth()->user()->role === 'admin' ? '🛡️ Administrator' : '👮 Petugas Jaga' }}
                        </div>
                    </a>
                    
                    <a href="/logout" class="h-10 w-10 rounded-full bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition shadow-sm border border-rose-100" title="Keluar Aplikasi">
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

    {{-- Script Global SweetAlert --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // Listener untuk notifikasi toast (sukses, error, info)
            Livewire.on('flash-message', (event) => {
                const { type = 'success', title = 'Berhasil!', text } = event;

                Swal.fire({
                    icon: type,
                    title: title,
                    text: text,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    background: '#fff',
                    customClass: {
                        popup: 'shadow-xl rounded-xl border border-gray-100 p-4'
                    }
                });
            });

            // Listener untuk dialog konfirmasi (misal: hapus data)
            Livewire.on('confirm-dialog', (event) => {
                const { title = 'Anda Yakin?', text = 'Tindakan ini tidak dapat dibatalkan!', confirm_event, confirm_params } = event;
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    showClass: {
                        popup: 'animate__animated animate__zoomIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__zoomOut'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch(confirm_event, confirm_params);
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>