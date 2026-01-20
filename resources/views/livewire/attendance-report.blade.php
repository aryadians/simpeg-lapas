<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER & FILTERS --}}
        <header class="mb-8 p-6 bg-white rounded-2xl shadow-lg border border-gray-100/80 animate__animated animate__fadeInDown">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Rekapitulasi Absensi
                    </h1>
                    <p class="text-gray-500 mt-1">Laporan kedisiplinan pegawai per bulan.</p>
                </div>

                <div class="flex gap-2 bg-gray-100 p-2 rounded-xl border border-gray-200/80">
                    <select wire:model.live="month" class="border-none bg-transparent focus:ring-0 font-bold text-gray-700 cursor-pointer pr-8">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->monthName }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="year" class="border-none bg-white rounded-lg shadow-sm focus:ring-1 focus:ring-indigo-500 font-bold text-gray-700 cursor-pointer border-l-0 pl-3 pr-8">
                        @for($y = 2024; $y <= date('Y'); $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </header>

        {{-- TABEL REKAP --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate__animated animate__fadeInUp">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b-2 border-gray-100">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600 uppercase tracking-wider">Pegawai</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600 uppercase tracking-wider text-center">Rincian Kehadiran</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600 uppercase tracking-wider text-center" style="min-width: 250px;">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($report as $userId => $data)
                        @if($data['total_kehadiran'] > 0) {{-- Hanya tampilkan user yang punya data absen --}}
                        <tr wire:key="{{ $userId }}" class="border-b border-gray-100 hover:bg-indigo-50/50 transition-colors duration-200">
                            {{-- Info Pegawai --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-11 w-11 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-base shrink-0 border-2 border-white shadow-sm">
                                        {{ strtoupper(substr($data['name'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $data['name'] }}</p>
                                        <p class="text-xs text-gray-500 font-mono">{{ $data['nip'] }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Rincian Kehadiran --}}
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Hadir: {{ $data['hadir'] }}</span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">Telat: {{ $data['terlambat'] }}</span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Alpha: {{ $data['alpha'] }}</span>
                                </div>
                            </td>

                            {{-- Visualisasi Progress Bar --}}
                            <td class="py-4 px-6">
                                @php
                                    $total = $data['total_kehadiran'] ?: 1;
                                    $hadirPercent = ($data['hadir'] / $total) * 100;
                                    $terlambatPercent = ($data['terlambat'] / $total) * 100;
                                    $alphaPercent = ($data['alpha'] / $total) * 100;
                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-2.5 flex overflow-hidden shadow-inner" title="Total Kehadiran: {{ $data['total_kehadiran'] }} hari">
                                    <div class="bg-emerald-500 h-2.5" style="width: {{ $hadirPercent }}%" title="Hadir Tepat Waktu: {{ $data['hadir'] }} hari"></div>
                                    <div class="bg-amber-500 h-2.5" style="width: {{ $terlambatPercent }}%" title="Terlambat: {{ $data['terlambat'] }} kali"></div>
                                    <div class="bg-red-500 h-2.5" style="width: {{ $alphaPercent }}%" title="Tanpa Keterangan: {{ $data['alpha'] }} kali"></div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <h3 class="text-xl font-semibold text-gray-700">Belum Ada Data Absensi</h3>
                                    <p class="mt-1">Tidak ada data absensi yang tercatat untuk periode ini.</p>
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