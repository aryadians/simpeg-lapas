<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Admin <span class="text-indigo-600">Console</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Real-time System Intelligence & Control</p>
                </div>
            </div>
        </header>

        {{-- Grid of Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate__animated animate__fadeInUp">

            {{-- Total Pegawai --}}
            <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-indigo-200 transition-all duration-500 group">
                <div class="h-16 w-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Staff</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $totalEmployees }}</p>
                </div>
            </div>

            {{-- Sedang Bertugas --}}
            <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-emerald-200 transition-all duration-500 group">
                <div class="h-16 w-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                     <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Duty</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $onDutyToday }}</p>
                </div>
            </div>

            {{-- Hadir Hari Ini --}}
            <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-sky-200 transition-all duration-500 group">
                <div class="h-16 w-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-sky-600 group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Clocked In</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $presentToday }}</p>
                </div>
            </div>

            {{-- Pengajuan Cuti --}}
            <a href="{{ route('cuti') }}" class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-amber-200 transition-all duration-500 group">
                <div class="h-16 w-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Leave Requests</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $pendingLeaveRequests }}</p>
                </div>
                <div class="text-gray-300 group-hover:text-amber-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
            
            {{-- Laporan Kejadian (24 Jam) --}}
            <a href="{{ route('incident-reports') }}" class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-rose-200 transition-all duration-500 group">
                <div class="h-16 w-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Incidents (24h)</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $recentIncidents }}</p>
                </div>
                 <div class="text-gray-300 group-hover:text-rose-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>

            {{-- Inventaris Terlambat --}}
            <a href="{{ route('inventaris') }}" class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-amber-600 transition-all duration-500 group">
                <div class="h-16 w-16 bg-amber-100 text-amber-700 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-amber-700 group-hover:text-white transition-all duration-500">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Overdue Items</p>
                    <p class="text-4xl font-black text-gray-900 tracking-tighter">{{ $overdueInventory }}</p>
                </div>
                 <div class="text-gray-300 group-hover:text-amber-700 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
            
        </div>
    </div>
</div>
