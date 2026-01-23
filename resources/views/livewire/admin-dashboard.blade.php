<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-6 animate__animated animate__fadeInDown">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Admin Dashboard</h1>
                    <p class="text-gray-500 mt-1">Ringkasan status operasional sistem.</p>
                </div>
            </div>
        </header>

        {{-- Grid of Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate__animated animate__fadeInUp">

            {{-- Total Pegawai --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Total Pegawai</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalEmployees }}</p>
                </div>
            </div>

            {{-- Sedang Bertugas --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center">
                     <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" ><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Sedang Bertugas Hari Ini</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $onDutyToday }}</p>
                </div>
            </div>

            {{-- Hadir Hari Ini --}}
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Hadir Hari Ini</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $presentToday }}</p>
                </div>
            </div>

            {{-- Pengajuan Cuti --}}
            <a href="{{ route('cuti') }}" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Pengajuan Cuti</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $pendingLeaveRequests }}</p>
                </div>
                <div class="ml-auto text-gray-400">
                    <svg class="h-5 w-5"  viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </div>
            </a>
            
            {{-- Laporan Kejadian (24 Jam) --}}
            <a href="{{ route('incident-reports') }}" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Kejadian (24 Jam)</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $recentIncidents }}</p>
                </div>
                 <div class="ml-auto text-gray-400">
                    <svg class="h-5 w-5"  viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </div>
            </a>

            {{-- Inventaris Terlambat --}}
            <a href="{{ route('inventaris') }}" class="bg-white p-6 rounded-2xl shadow-md border border-gray-100/80 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 w-16 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold">Inventaris Terlambat</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $overdueInventory }}</p>
                </div>
                 <div class="ml-auto text-gray-400">
                    <svg class="h-5 w-5"  viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </div>
            </a>
            
        </div>
    </div>
</div>
