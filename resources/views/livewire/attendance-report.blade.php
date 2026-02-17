<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER & FILTERS --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center lg:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Attendance <span class="text-emerald-600">Sync</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Rekapitulasi Kedisiplinan & Kehadiran Pegawai</p>
                </div>

                <div class="flex items-center gap-3 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 shadow-inner relative z-10">
                    <div class="flex gap-1">
                        <select wire:model.live="month" class="bg-white border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 shadow-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}">{{ strtoupper(\Carbon\Carbon::create(null, $m)->monthName) }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="year" class="bg-white border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 shadow-sm">
                            @for($y = 2024; $y <= date('Y'); $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </header>

        {{-- TABEL REKAP --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden animate__animated animate__fadeInUp">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Personnel Identity</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Status Breakdown</th>
                            <th class="py-6 px-8 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center" style="min-width: 300px;">Efficiency Index</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($report as $userId => $data)
                        <tr wire:key="{{ $userId }}" class="hover:bg-indigo-50/30 transition-colors group">
                            {{-- Info Pegawai --}}
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-5">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-black text-gray-400 text-lg shadow-inner group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-white transition-all duration-500 uppercase">
                                        {{ strtoupper(substr($data['name'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-800 uppercase tracking-tight group-hover:text-emerald-600 transition-colors">{{ $data['name'] }}</p>
                                        <p class="text-[10px] text-gray-400 font-black font-mono tracking-widest mt-0.5 uppercase">{{ $data['nip'] }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Rincian Kehadiran --}}
                            <td class="py-6 px-8 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <div class="flex flex-col items-center px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 min-w-[60px]">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Hadir</span>
                                        <span class="text-sm font-black text-emerald-600">{{ $data['hadir'] }}</span>
                                    </div>
                                    <div class="flex flex-col items-center px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 min-w-[60px]">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Telat</span>
                                        <span class="text-sm font-black text-amber-500">{{ $data['terlambat'] }}</span>
                                    </div>
                                    <div class="flex flex-col items-center px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 min-w-[60px]">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alpha</span>
                                        <span class="text-sm font-black text-rose-500">{{ $data['alpha'] }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Visualisasi Progress Bar --}}
                            <td class="py-6 px-8">
                                @php
                                    $total = $data['total_kehadiran'];
                                    $hasData = $total > 0;
                                    $hadirPercent = $hasData ? ($data['hadir'] / $total) * 100 : 0;
                                    $terlambatPercent = $hasData ? ($data['terlambat'] / $total) * 100 : 0;
                                    $alphaPercent = $hasData ? ($data['alpha'] / $total) * 100 : 0;
                                @endphp
                                @if($hasData)
                                <div class="space-y-2">
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 flex overflow-hidden shadow-inner border border-gray-200/50">
                                        <div class="bg-emerald-500 h-full transition-all duration-700" style="width: {{ $hadirPercent }}%"></div>
                                        <div class="bg-amber-400 h-full transition-all duration-700" style="width: {{ $terlambatPercent }}%"></div>
                                        <div class="bg-rose-500 h-full transition-all duration-700" style="width: {{ $alphaPercent }}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center px-1">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Consistency: {{ round($hadirPercent) }}%</span>
                                        <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $total }} Operations</span>
                                    </div>
                                </div>
                                @else
                                <div class="text-center">
                                    <span class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] italic">No Operational History</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30 grayscale">
                                    <span class="text-7xl mb-6">📊</span>
                                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">Registry Silent</h3>
                                    <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em]">No staff records identified for this period.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
