<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 relative overflow-hidden">
    <!-- Header -->
    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-800">Presensi Kehadiran</h3>
        <p class="text-sm text-gray-500 font-mono">{{ $currentDateDisplay ?? \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
    </div>

    @if(!$todayRoster)
        <!-- State: Off Duty -->
        <div class="pt-6 text-center">
             <div class="bg-gray-50 rounded-xl p-8 border-2 border-dashed border-gray-200 animate-fade-in-down">
                <span class="text-5xl block mb-3">🏖️</span>
                <p class="text-gray-700 font-bold text-lg">Anda Libur</p>
                <p class="text-sm text-gray-500 mt-1">Tidak ada jadwal dinas hari ini.</p>
            </div>
        </div>
    @else
        <!-- State: Has Roster -->
        <div class="pt-5">
            <!-- Schedule Info -->
            <div class="mb-5 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Jadwal Anda Hari Ini</p>
                <div class="flex items-center gap-3 mt-2">
                    <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg shrink-0">
                        {{ substr($todayRoster->shift->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-base font-extrabold text-indigo-900 leading-tight">
                            {{ $todayRoster->shift->name }}
                        </p>
                        <p class="text-xs text-indigo-600 font-medium">
                            🕒 {{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Actions & Status -->
            <div class="animate-fade-in-down">
                @if(!$attendance)
                    <!-- Action: Clock In -->
                    <button wire:click="clockIn" wire:loading.attr="disabled" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-lg">
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span wire:loading.remove>Absen Masuk</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @elseif(!$attendance->clock_out)
                    <!-- Status: Clocked In, waiting for Clock Out -->
                    <div class="bg-white border-2 border-emerald-500 rounded-xl p-4 shadow-sm mb-4">
                        <p class="text-xs text-emerald-700 mb-2 font-bold uppercase tracking-wide text-center">Telah Absen Masuk</p>
                        <div class="flex justify-between items-center text-center">
                            <div class="w-1/2">
                                <span class="text-gray-500 text-xs">Jam Masuk</span>
                                <span class="font-bold text-gray-800 font-mono text-xl block">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                            </div>
                            <div class="w-1/2">
                                <span class="text-gray-500 text-xs">Status</span>
                                <span class="block font-bold text-sm mt-1 {{ $attendance->status == 'terlambat' ? 'text-red-600' : 'text-emerald-600' }}">
                                    @if($attendance->status == 'terlambat') ⚠️ TERLAMBAT @else ✅ TEPAT WAKTU @endif
                                </span>
                            </div>
                        </div>
                    </div>
                     <button wire:click="clockOut" wire:confirm="Yakin ingin mengakhiri jam dinas sekarang?" class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-lg">
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span>Absen Pulang</span>
                    </button>
                @else
                    <!-- State: Finished -->
                    <div class="bg-emerald-50 rounded-xl p-6 text-center border-2 border-emerald-200">
                        <span class="text-4xl block mb-2">👋</span>
                        <p class="text-emerald-800 font-bold text-lg">Tugas Selesai!</p>
                        <div class="text-sm text-emerald-700 mt-2 font-mono">
                            Masuk: {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }} | Pulang: {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Footer with Server Time -->
    <div class="pt-4 mt-4 border-t border-gray-100 text-right">
         <p class="text-[10px] text-gray-400 font-mono">
            Server Time: {{ $currentTime ?? \Carbon\Carbon::now()->format('H:i:s') }}
        </p>
    </div>
</div>
