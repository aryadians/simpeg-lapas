<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="manifest" href="/manifest.json">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 600: '#4f46e5', 700: '#4338ca' },
                        rose: { 500: '#f43f5e', 600: '#e11d48' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] antialiased text-gray-800 dark:text-gray-200 transition-colors duration-500" 
      x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        }
      }"
      :class="{ 'dark': darkMode }">

    {{-- SUPER NAVBAR --}}
    <nav x-data="{ open: false, dropOp: false, dropPers: false, dropAdmin: false }" class="bg-white/80 dark:bg-black/80 backdrop-blur-lg border-b border-gray-100 dark:border-white/10 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20">
                
                {{-- KIRI: Logo --}}
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 md:h-11 md:w-11 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/30 shrink-0 transform -rotate-3">
                        {{ substr(config('app.name'), 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm md:text-base font-black text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                            {{ config('app.name') }}
                        </span>
                        <span class="text-[8px] font-black text-indigo-500 uppercase tracking-[0.3em] mt-1">Intelligence System</span>
                    </div>
                </div>

                {{-- TENGAH: Desktop Menu (Categorized) --}}
                <div class="hidden lg:flex items-center gap-1">
                    
                    {{-- Jadwal Group --}}
                    <a href="/" wire:navigate class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->is('/') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-indigo-600' }}">Jadwal</a>
                    
                    {{-- Dropdown Operasional --}}
                    <div class="relative" @click.away="dropOp = false">
                        <button @click="dropOp = !dropOp" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-1 {{ request()->is('patroli*') || request()->is('laporan*') ? 'text-indigo-600' : 'text-gray-400' }}">
                            Operations
                            <svg class="h-3 w-3 transition-transform" :class="dropOp ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="dropOp" x-transition x-cloak class="absolute top-full mt-2 w-48 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-white/10 p-2 overflow-hidden">
                            <a href="/patroli" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">🛡️ Patroli QR</a>
                            <a href="/laporan" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">📋 Laporan Jaga</a>
                            <a href="/tukar-dinas" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">🔄 Tukar Dinas</a>
                        </div>
                    </div>

                    {{-- Dropdown Personalia --}}
                    <div class="relative" @click.away="dropPers = false">
                        <button @click="dropPers = !dropPers" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-1 {{ request()->is('cuti*') || request()->is('dokumen*') ? 'text-indigo-600' : 'text-gray-400' }}">
                            Resources
                            <svg class="h-3 w-3 transition-transform" :class="dropPers ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="dropPers" x-transition x-cloak class="absolute top-full mt-2 w-48 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-white/10 p-2 overflow-hidden">
                            <a href="/cuti" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">🏖️ E-Cuti</a>
                            <a href="/dokumen" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">🗄️ Document Vault</a>
                            <a href="/kalender-absen" wire:navigate class="block px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-white/5 transition-all">📊 Activity Journal</a>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="h-6 w-px bg-gray-100 dark:bg-white/10 mx-2"></div>
                    
                    {{-- Dropdown Admin --}}
                    <div class="relative" @click.away="dropAdmin = false">
                        <button @click="dropAdmin = !dropAdmin" class="px-4 py-2 rounded-xl bg-black text-white text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                            Command
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="dropAdmin" x-transition x-cloak class="absolute top-full right-0 mt-2 w-56 bg-gray-900 rounded-2xl shadow-2xl border border-white/5 p-2 overflow-hidden">
                            <a href="/admin/dashboard" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all border-b border-white/5 mb-1">🚀 Admin Console</a>
                            <a href="/pegawai" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">👥 Staff Assets</a>
                            <a href="/inventaris" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">📦 Inventory Vault</a>
                            <a href="/laporan-kejadian" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">📝 Incident Reports</a>
                            <a href="/laporan-tukin" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">💰 Payroll Analytics</a>
                            <a href="/post-assignment" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">📍 Sector Deploy</a>
                            <div class="h-px bg-white/5 my-1"></div>
                            <a href="/audit-logs" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-indigo-400 hover:bg-white/5 transition-all">🔍 Audit Intel</a>
                            <a href="/settings" wire:navigate class="block px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">⚙️ System Settings</a>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- KANAN: Tools & Mobile Trigger --}}
                <div class="flex items-center gap-2">
                    <button @click="toggleDarkMode()" class="h-10 w-10 rounded-xl bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 flex items-center justify-center transition-all border border-gray-100 dark:border-white/5">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14.5 12a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>
                    </button>

                    <livewire:notification-bell />
                    <livewire:panic-handler />
                    
                    <button @click="open = !open" class="lg:hidden h-10 w-10 rounded-xl bg-gray-50 dark:bg-white/5 text-gray-500 flex items-center justify-center border border-gray-100 dark:border-white/5">
                        <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h16m-7 6h7" /></svg>
                        <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    
                    <div class="hidden lg:flex items-center gap-3 ml-2">
                        <a href="/profil" wire:navigate class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-600 border border-indigo-100 dark:border-indigo-500 flex items-center justify-center text-indigo-600 dark:text-white font-black text-xs uppercase">{{ substr(auth()->user()->name, 0, 2) }}</a>
                        <a href="/logout" class="h-10 w-10 rounded-full bg-rose-50 dark:bg-rose-600 text-rose-500 dark:text-white flex items-center justify-center hover:bg-rose-500 transition-all shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- MOBILE MENU (FULL LIST) --}}
        <div x-show="open" x-cloak class="lg:hidden border-t border-gray-100 dark:border-white/5 bg-white dark:bg-black animate__animated animate__slideInDown animate__faster overflow-y-auto max-h-[80vh]">
            <div class="p-4 space-y-1">
                <p class="px-4 py-2 text-[8px] font-black text-gray-400 uppercase tracking-[0.3em]">Core Navigation</p>
                <a href="/" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] {{ request()->is('/') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500' }}">🗓️ Jadwal Dinas</a>
                <a href="/patroli" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">🛡️ Patroli Checkpoint</a>
                <a href="/laporan" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">📋 Laporan Jaga</a>
                <a href="/cuti" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">🏖️ E-Cuti Online</a>
                <a href="/dokumen" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">🗄️ Document Vault</a>
                <a href="/kalender-absen" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">📊 Activity Journal</a>
                
                @if(auth()->user()->role === 'admin')
                <p class="px-4 py-2 mt-4 text-[8px] font-black text-indigo-500 uppercase tracking-[0.3em]">Command Center</p>
                <a href="/admin/dashboard" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500 italic">🚀 Admin Console</a>
                <a href="/pegawai" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">👥 Staff Management</a>
                <a href="/inventaris" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">📦 Inventory Control</a>
                <a href="/laporan-kejadian" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">📝 Incident Registry</a>
                <a href="/settings" class="block p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500">⚙️ System Config</a>
                @endif

                <div class="h-px bg-gray-100 dark:bg-white/5 my-4"></div>
                <a href="/logout" class="block p-4 text-rose-500 font-black text-xs uppercase tracking-widest text-center bg-rose-50 dark:bg-rose-900/20 rounded-2xl">Terminal Session</a>
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
                Swal.fire({ icon: data.type || 'success', title: data.title || 'Signal', text: data.text || '', timer: 3000, toast: true, position: 'top-end', showConfirmButton: false, background: localStorage.getItem('darkMode') === 'true' ? '#111827' : '#fff', color: localStorage.getItem('darkMode') === 'true' ? '#fff' : '#000' });
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
                    background: localStorage.getItem('darkMode') === 'true' ? '#111827' : '#fff',
                    color: localStorage.getItem('darkMode') === 'true' ? '#fff' : '#000',
                    customClass: {
                        popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                        title: 'text-xl font-black uppercase tracking-tighter',
                        confirmButton: 'rounded-xl px-6 py-3 text-[10px] font-black uppercase tracking-widest shadow-lg',
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
