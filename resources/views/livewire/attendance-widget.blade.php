<div class="bg-white rounded-2xl shadow-lg border border-indigo-50 p-6 relative overflow-hidden group">
    
    {{-- Dekorasi Background --}}
    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -translate-y-1/2 translate-x-1/2 group-hover:bg-indigo-200 transition-colors"></div>

    <div class="relative z-10">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Presensi Kehadiran</h3>
        <p class="text-sm text-gray-500 mb-4">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>

        @if(!$todayRoster)
            {{-- Kondisi 1: Tidak Ada Jadwal --}}
            <div class="bg-gray-50 rounded-xl p-4 text-center border border-dashed border-gray-300">
                <span class="text-3xl block mb-2">🏖️</span>
                <p class="text-gray-500 font-medium text-sm">Anda libur hari ini.</p>
            </div>

        @else
            {{-- Kondisi 2: Punya Jadwal --}}
            <div class="flex items-center gap-3 mb-4 bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold">
                    {{ substr($todayRoster->shift->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-indigo-400 uppercase">Jadwal Dinas</p>
                    <p class="text-sm font-bold text-indigo-900">
                        {{ $todayRoster->shift->name }} 
                        <span class="font-normal text-xs ml-1">
                            ({{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }})
                        </span>
                    </p>
                </div>
            </div>

            @if(!$attendance)
                {{-- Belum Absen Masuk --}}
                <button wire:click="clockIn" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-1 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    Absen Masuk
                </button>

            @elseif(!$attendance->clock_out)
                {{-- Sudah Masuk, Belum Pulang --}}
                <div class="mb-4 text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $attendance->status == 'terlambat' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        Status: {{ strtoupper($attendance->status) }}
                        @if($attendance->status == 'terlambat') ⏳ @else ✔ @endif
                    </span>
                    <p class="text-xs text-gray-400 mt-2">Masuk pukul: {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</p>
                </div>
                
                <button wire:click="clockOut" class="w-full py-3 bg-white border-2 border-red-500 text-red-500 hover:bg-red-50 font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Absen Pulang
                </button>

            @else
                {{-- Selesai Dinas --}}
                <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                    <span class="text-3xl block mb-2">👋</span>
                    <p class="text-green-800 font-bold text-sm">Dinas Selesai</p>
                    <p class="text-green-600 text-xs mt-1">Sampai jumpa besok!</p>
                </div>
            @endif

        @endif
    </div>
</div>