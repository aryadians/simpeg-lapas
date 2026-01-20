<div class="min-h-screen bg-gray-100 p-8 font-sans relative">
    
    {{-- BAGIAN 1: HEADER & TOMBOL NAVIGASI --}}
    <div class="max-w-7xl mx-auto mb-8 flex justify-between items-end animate-fade-in-down">
        <div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 drop-shadow-sm">
                Jadwal Dinas
            </h1>
            <p class="text-gray-500 mt-2 text-lg">Monitoring & Distribusi Shift Petugas</p>
        </div>
        
        <div class="flex space-x-3">
            {{-- TOMBOL BARU: AUTO GENERATE --}}
            <button wire:click="generateSchedule" 
                    wire:confirm="Yakin ingin membuat jadwal otomatis untuk bulan ini? Jadwal lama di bulan ini akan dihapus/ditimpa."
                    class="px-5 py-2.5 font-bold text-white bg-emerald-500 rounded-xl shadow-lg hover:bg-emerald-600 transition transform hover:-translate-y-1 flex items-center gap-2 mr-2 border-2 border-emerald-400">
                <span>⚡ Auto Generate</span>
                {{-- TOMBOL BARU: CETAK PDF --}}
            <a href="/cetak-laporan" target="_blank"
               class="px-5 py-2.5 font-bold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-lg hover:bg-gray-50 transition transform hover:-translate-y-1 flex items-center gap-2 mr-2">
                <span>🖨️ Cetak PDF</span>
            </a>
            </button>

            {{-- Navigasi Tanggal --}}
            <button wire:click="prevDays" class="px-5 py-2.5 font-bold text-white bg-indigo-500 rounded-xl shadow-lg hover:bg-indigo-600 transition transform hover:-translate-y-1">
                ←
            </button>
            <button wire:click="nextDays" class="px-5 py-2.5 font-bold text-white bg-purple-500 rounded-xl shadow-lg hover:bg-purple-600 transition transform hover:-translate-y-1">
                →
            </button>
        </div>
    </div>

    {{-- BAGIAN 2: WIDGET STATISTIK & GRAFIK (EXECUTIVE DASHBOARD) --}}
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 animate-fade-in-up">
        
        {{-- Kartu 1: Total Personil --}}
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-indigo-50 flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-24 w-24 bg-indigo-100 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pegawai</p>
                <h3 class="text-3xl font-extrabold text-indigo-900 mt-1">{{ $todayStats['total_pegawai'] ?? 0 }}</h3>
                <p class="text-xs text-indigo-400 mt-1">Terdaftar di sistem</p>
            </div>
            <div class="h-12 w-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xl shadow-indigo-200 shadow-lg z-10">
                👮‍♂️
            </div>
        </div>

        {{-- Kartu 2: Dinas Malam Hari Ini --}}
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-50 flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-24 w-24 bg-purple-100 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Dinas Malam (Hari Ini)</p>
                <h3 class="text-3xl font-extrabold text-purple-900 mt-1">{{ $todayStats['dinas_malam'] ?? 0 }}</h3>
                <p class="text-xs text-purple-500 mt-1">Personil Siaga</p>
            </div>
            <div class="h-12 w-12 bg-purple-600 rounded-xl flex items-center justify-center text-white text-xl shadow-purple-200 shadow-lg z-10">
                🌙
            </div>
        </div>

        {{-- Kartu 3: Grafik Donat Distribusi Shift --}}
        <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-50 flex items-center gap-4 relative">
            <div class="w-24 h-24 flex-shrink-0 relative">
                <canvas id="shiftChart"></canvas>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Tren Shift Bulan Ini</p>
                <div class="space-y-1">
                    <div class="flex items-center text-xs text-gray-600">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span> Pagi
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span> Siang
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <span class="w-2 h-2 rounded-full bg-slate-800 mr-2"></span> Malam
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 3: GRID JADWAL HARIAN (KARTU 3D) --}}
    <div class="flex gap-6 overflow-x-auto pb-10 custom-scrollbar px-4 md:px-0">
        @foreach($dateRange as $date)
            @php
                $dayName = \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd');
                $dayDate = \Carbon\Carbon::parse($date)->format('d M Y');
                $isToday = $date == date('Y-m-d');
            @endphp

            <div class="flex-shrink-0 w-80 relative group transition-all duration-500 hover:scale-[1.01]">
                {{-- Efek Glow --}}
                <div class="absolute -inset-0.5 bg-gradient-to-r {{ $isToday ? 'from-pink-600 to-purple-600' : 'from-gray-300 to-gray-400' }} rounded-2xl blur opacity-30 group-hover:opacity-75 transition duration-500"></div>
                
                <div class="relative bg-white rounded-2xl shadow-xl flex flex-col h-full border border-white/50 overflow-hidden">
                    <div class="p-5 {{ $isToday ? 'bg-indigo-600' : 'bg-gray-50' }} border-b border-gray-100">
                        <h3 class="text-2xl font-bold {{ $isToday ? 'text-white' : 'text-gray-800' }}">{{ $dayName }}</h3>
                        <p class="{{ $isToday ? 'text-indigo-100' : 'text-gray-500' }} text-sm font-medium">{{ $dayDate }}</p>
                    </div>

                    <div class="p-4 space-y-3 flex-1 overflow-y-auto max-h-[450px] custom-scrollbar">
                        @if(isset($rosters[$date]))
                            @foreach($rosters[$date] as $roster)
                                {{-- KARTU PEGAWAI (CLICKABLE) --}}
                                <div wire:click="editRoster({{ $roster->id }})" 
                                     class="flex items-center p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-300 cursor-pointer transition-all duration-200 transform hover:-translate-y-1 active:scale-95 group/item">
                                    
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-md shrink-0">
                                        {{ substr($roster->user->name, 0, 2) }}
                                    </div>

                                    <div class="ml-3 overflow-hidden">
                                        <p class="text-sm font-bold text-gray-700 truncate group-hover/item:text-indigo-600 transition-colors">
                                            {{ $roster->user->name }}
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1
                                            {{ $roster->shift->is_overnight ? 'bg-slate-800 text-yellow-400' : 
                                               ($roster->shift->name == 'Regu Pagi' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($roster->shift->name == 'Regu Siang' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ $roster->shift->name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-10 opacity-50">
                                <p class="text-gray-400 text-sm">Tidak ada jadwal</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- BAGIAN 4: MODAL POPUP (GLASSMORPHISM) --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm transition-all"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100">
        
        <div class="relative w-full max-w-md p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl border border-white/20">
                
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-bold text-gray-900">Ubah Jadwal Dinas</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-900 w-8 h-8 flex justify-center items-center rounded-lg hover:bg-gray-100 transition">
                        ✕
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold">
                            {{ substr($selectedRosterName, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-xs text-indigo-500 uppercase font-bold tracking-wider">Petugas</p>
                            <p class="text-lg font-bold text-indigo-900 leading-none">{{ $selectedRosterName }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Shift Baru</label>
                        <select wire:model="selectedShiftId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 transition">
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                    <button wire:click="saveRoster" type="button" class="text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-sm px-5 py-2.5 shadow-md transform transition hover:-translate-y-0.5 w-full">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- BAGIAN 5: SCRIPT CHART.JS --}}
<script>
    document.addEventListener('livewire:init', () => {
        // Cek apakah elemen chart ada untuk menghindari error
        const ctx = document.getElementById('shiftChart');
        if (!ctx) return;

        const stats = @json($shiftStats);

        // Hancurkan chart lama jika ada (untuk mencegah duplikat saat refresh Livewire)
        if (window.myShiftChart) {
            window.myShiftChart.destroy();
        }
        
        window.myShiftChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(stats),
                datasets: [{
                    data: Object.values(stats),
                    backgroundColor: [
                        '#fbbf24', // Kuning (Pagi)
                        '#60a5fa', // Biru (Siang)
                        '#1e293b', // Gelap (Malam)
                        '#94a3b8', // Abu
                        '#a78bfa'  // Ungu
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%', 
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>