<div class="bg-white rounded-2xl shadow-lg border border-indigo-50 p-6 relative overflow-hidden group">
    
    {{-- Dekorasi Background --}}
    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -translate-y-1/2 translate-x-1/2 group-hover:bg-indigo-200 transition-colors"></div>

    <div class="relative z-10">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Presensi Kehadiran</h3>
        
        {{-- TANGGAL HARI INI (DEBUG INFO) --}}
        <div class="border-b border-gray-100 pb-3 mb-4">
            <p class="text-sm text-gray-500 font-medium">
                {{ $currentDateDisplay ?? \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
            {{-- Debug Jam Server (Bisa dihapus nanti jika sudah production) --}}
            <p class="text-[10px] text-gray-300 font-mono mt-1">
                Server Time: {{ $currentTime ?? \Carbon\Carbon::now()->format('H:i') }}
            </p>
        </div>

        @if(!$todayRoster)
            {{-- Kondisi 1: Tidak Ada Jadwal (Libur / Belum Generate) --}}
            <div class="bg-gray-50 rounded-xl p-6 text-center border border-dashed border-gray-300 flex flex-col items-center justify-center h-40">
                <span class="text-4xl block mb-2 animate-bounce">🏖️</span>
                <p class="text-gray-600 font-bold text-sm">Anda Libur Hari Ini</p>
                <p class="text-xs text-gray-400 mt-1">Tidak ada jadwal dinas ditemukan.</p>
            </div>

        @else
            {{-- Kondisi 2: Punya Jadwal --}}
            <div class="flex items-center gap-3 mb-6 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <div class="h-12 w-12 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg shrink-0">
                    {{ substr($todayRoster->shift->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Jadwal Dinas</p>
                    <p class="text-base font-extrabold text-indigo-900 leading-tight">
                        {{ $todayRoster->shift->name }}
                    </p>
                    <p class="text-xs text-indigo-600 mt-0.5 font-medium">
                        🕒 {{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }}
                    </p>
                </div>
            </div>

            @if(!$attendance)
                {{-- Belum Absen Masuk --}}
                <div class="space-y-3">
                    <button wire:click="clockIn" 
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group-hover:from-indigo-600 group-hover:to-purple-600">
                        <span wire:loading.remove>👆 Absen Masuk</span>
                        <span wire:loading>⏳ Memproses...</span>
                    </button>
                    <p class="text-center text-xs text-gray-400">Klik tombol di atas saat tiba di lokasi.</p>
                </div>

            @elseif(!$attendance->clock_out)
                {{-- Sudah Masuk, Belum Pulang --}}
                <div class="mb-6 text-center bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                    <p class="text-xs text-gray-400 mb-2 font-medium uppercase tracking-wide">Status Kehadiran</p>
                    
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border animate-pop-in
                        {{ $attendance->status == 'terlambat' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                        @if($attendance->status == 'terlambat') ⚠️ TERLAMBAT @else ✅ HADIR TEPAT WAKTU @endif
                    </span>
                    
                    <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center text-xs">
                        <span class="text-gray-400">Masuk:</span>
                        <span class="font-bold text-gray-800 font-mono">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                    </div>
                </div>
                
                <button wire:click="clockOut" 
                        wire:confirm="Yakin ingin mengakhiri jam dinas sekarang?"
                        class="w-full py-3 bg-white border-2 border-rose-500 text-rose-500 hover:bg-rose-500 hover:text-white font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <span>🏠 Absen Pulang</span>
                </button>

            @else
                {{-- Selesai Dinas --}}
                <div class="bg-emerald-50 rounded-xl p-6 text-center border border-emerald-200 h-40 flex flex-col items-center justify-center">
                    <span class="text-4xl block mb-2 animate-pulse">👋</span>
                    <p class="text-emerald-800 font-bold text-base">Dinas Selesai!</p>
                    <p class="text-emerald-600 text-xs mt-1 px-4 leading-relaxed">Terima kasih atas dedikasi Anda hari ini. Hati-hati di jalan.</p>
                </div>
            @endif

        @endif
    </div>
</div>