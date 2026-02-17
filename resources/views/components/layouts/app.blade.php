<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? config('app.name') }}</title>
    
    {{-- Tailwind & CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="manifest" href="/manifest.json">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        @media (min-width: 1024px) {
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #f1f5f9; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 600: '#4f46e5', 700: '#4338ca' },
                        rose: { 500: '#f43f5e', 600: '#e11d48' }
                    },
                    borderRadius: { '3xl': '1.5rem', '4xl': '2rem' }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 antialiased text-gray-800">

    <nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20">
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="h-10 w-10 md:h-12 md:w-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/30 shrink-0">
                        {{ substr(config('app.name'), 0, 1) }}
                    </div>
                    <span class="text-lg md:text-xl font-black text-gray-900 tracking-tighter uppercase hidden xs:block">
                        {{ config('app.name') }}
                    </span>
                </div>

                <div class="hidden lg:flex items-center gap-1">
                    <a href="/" wire:navigate class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request()->is('/') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Jadwal</a>
                    <a href="/patroli" wire:navigate class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request()->is('patroli*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Patroli</a>
                    <a href="/cuti" wire:navigate class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request()->is('cuti*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Cuti</a>
                    <a href="/kalender-absen" wire:navigate class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('attendance.calendar') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Activity</a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Admin</a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <livewire:notification-bell />
                    <livewire:panic-handler />
                    <button @click="open = !open" class="lg:hidden h-10 w-10 rounded-xl bg-gray-50 text-gray-500 flex items-center justify-center border border-gray-100">
                        <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h16m-7 6h7" /></svg>
                        <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <div class="hidden lg:flex items-center gap-3 ml-2">
                        <a href="/profil" wire:navigate class="h-10 w-10 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xs uppercase">{{ substr(auth()->user()->name, 0, 2) }}</a>
                        <a href="/logout" class="h-10 w-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="open" x-cloak class="lg:hidden border-t border-gray-100 bg-white animate__animated animate__fadeIn">
            <div class="p-4 space-y-2">
                <a @click="open = false" href="/" wire:navigate class="block p-4 rounded-2xl font-black uppercase tracking-widest text-xs {{ request()->is('/') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500' }}">Jadwal</a>
                <a @click="open = false" href="/patroli" wire:navigate class="block p-4 rounded-2xl font-black uppercase tracking-widest text-xs {{ request()->is('patroli*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500' }}">Patroli</a>
                <a @click="open = false" href="/cuti" wire:navigate class="block p-4 rounded-2xl font-black uppercase tracking-widest text-xs {{ request()->is('cuti*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500' }}">Cuti</a>
                <a @click="open = false" href="/kalender-absen" wire:navigate class="block p-4 rounded-2xl font-black uppercase tracking-widest text-xs {{ request()->routeIs('attendance.calendar') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500' }}">Activity</a>
                <div class="h-px bg-gray-100 my-4"></div>
                <a href="/logout" class="block p-4 text-rose-500 font-black text-xs uppercase tracking-widest text-center bg-rose-50 rounded-xl">Logout</a>
            </div>
        </div>
    </nav>

    <main class="min-h-[calc(100vh-80px)]">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('flash-message', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                Swal.fire({ icon: data.type || 'success', title: data.title || 'Status', text: data.text || '', timer: 3000, toast: true, position: 'top-end', showConfirmButton: false });
            });

            const handleConfirm = (event) => {
                const data = event.detail || (Array.isArray(event) ? event[0] : event);
                Swal.fire({ 
                    title: data.title || 'Confirm Action', 
                    text: data.text || '', 
                    icon: data.icon || 'warning', 
                    showCancelButton: true, 
                    confirmButtonColor: '#4f46e5', 
                    cancelButtonColor: '#f43f5e', 
                    confirmButtonText: 'Execute',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                        title: 'text-xl font-black uppercase tracking-tighter text-gray-900',
                        htmlContainer: 'text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-2',
                        confirmButton: 'rounded-xl px-6 py-3 text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20',
                        cancelButton: 'rounded-xl px-6 py-3 text-[10px] font-black uppercase tracking-widest'
                    }
                }).then((result) => {
                    if (result.isConfirmed) { Livewire.dispatch(data.confirm_event, data.confirm_params || {}); }
                });
            };

            window.addEventListener('confirm-dialog', handleConfirm);
            Livewire.on('confirm-dialog', handleConfirm);
        });
    </script>
    @stack('scripts')
</body>
</html>
