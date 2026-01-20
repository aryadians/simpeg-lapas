<div class="p-4 sm:p-6 md:p-8 font-sans antialiased" style="perspective: 1000px;">

    {{-- ====================================================================== --}}
    {{-- HEADER: JUDUL, NAVIGASI & AKSI
    {{-- ====================================================================== --}}
    <header class="max-w-7xl mx-auto mb-8 animate-fade-in-down">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 p-4 bg-white/60 backdrop-blur-lg rounded-2xl shadow-md border border-gray-100">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Dashboard Jadwal</h1>
                <p class="text-gray-500">Bulan: <span class="font-bold text-indigo-600">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('F Y') }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="prevDays" title="Bulan Sebelumnya" class="p-3 h-12 w-12 rounded-xl bg-white hover:bg-indigo-50 text-indigo-600 border-2 border-indigo-100 shadow-sm transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button wire:click="nextDays" title="Bulan Berikutnya" class="p-3 h-12 w-12 rounded-xl bg-white hover:bg-purple-50 text-purple-600 border-2 border-purple-100 shadow-sm transition-all duration-300 transform hover:scale-110 active:scale-95">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
                
                <a href="/cetak-laporan" target="_blank" title="Cetak Laporan" class="p-3 h-12 w-12 rounded-xl bg-white hover:bg-gray-50 text-gray-600 border-2 border-gray-100 shadow-sm transition-all duration-300 transform hover:scale-110 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                </a>
                
                @if(auth()->user()->role === 'admin')
                <button onclick="Livewire.dispatch('confirm-dialog', { title: 'Anda Yakin?', text: 'Jadwal lama di bulan ini akan dihapus/ditimpa.', confirm_event: 'generate-schedule-confirmed', confirm_params: {} })"
                        title="Generate Jadwal Otomatis"
                        class="p-3 h-12 w-auto rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white border-2 border-emerald-300 shadow-lg shadow-emerald-500/30 transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <span class="hidden md:inline">Generate</span>
                </button>
                @endif
            </div>
        </div>
    </header>

    {{-- ====================================================================== --}}
    {{-- KONTEN UTAMA: WIDGET & STATISTIK
    {{-- ====================================================================== --}}
    <main class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        {{-- KOLOM KIRI: WIDGET ABSEN & CHART --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="animate-pop-in">
                <livewire:attendance-widget :todayRoster="$todaysRosterForUser" />
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 animate-pop-in" style="animation-delay: 100ms;">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Komposisi Shift</h3>
                <div class="h-48 w-full flex justify-center items-center">
                    <canvas id="shiftChart"></canvas>
                </div>
                 <div class="mt-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600"><span class="w-3 h-3 rounded-full bg-yellow-400 mr-3"></span> Regu Pagi</div>
                    <div class="flex items-center text-sm text-gray-600"><span class="w-3 h-3 rounded-full bg-blue-400 mr-3"></span> Regu Siang</div>
                    <div class="flex items-center text-sm text-gray-600"><span class="w-3 h-3 rounded-full bg-slate-800 mr-3"></span> Regu Malam</div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STATISTIK & JADWAL --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl p-6 shadow-xl shadow-indigo-500/30 transition-all duration-300 transform-gpu hover:scale-105 hover:[transform:rotateY(-10deg)] animate-pop-in" style="animation-delay: 200ms;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium opacity-80">Total Pegawai</p>
                            <h3 class="text-4xl font-extrabold mt-1">{{ $todayStats['total_pegawai'] ?? 0 }}</h3>
                        </div>
                        <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">👮‍♂️</div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-fuchsia-600 text-white rounded-2xl p-6 shadow-xl shadow-purple-500/30 transition-all duration-300 transform-gpu hover:scale-105 hover:[transform:rotateY(10deg)] animate-pop-in" style="animation-delay: 300ms;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium opacity-80">Dinas Malam Ini</p>
                            <h3 class="text-4xl font-extrabold mt-1">{{ $todayStats['dinas_malam'] ?? 0 }}</h3>
                        </div>
                         <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">🌙</div>
                    </div>
                </div>
            </div>

            {{-- Jadwal Harian --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 animate-fade-in-up" style="animation-delay: 400ms;">
                <h3 class="text-lg font-bold text-gray-800 px-2 mb-3">Jadwal Harian</h3>
                <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                    @forelse($dateRange as $date)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $isToday = $carbonDate->isToday();
                        @endphp
                        <div class="flex-shrink-0 w-72 border-2 {{ $isToday ? 'border-indigo-500' : 'border-gray-200' }} bg-gray-50 rounded-xl transition-all duration-300 group">
                            <div class="p-4 border-b-2 {{ $isToday ? 'border-indigo-500 bg-indigo-500 text-white' : 'border-gray-200 bg-gray-200 text-gray-700' }}">
                                <p class="font-bold text-lg">{{ $carbonDate->translatedFormat('l') }}</p>
                                <p class="text-sm opacity-80">{{ $carbonDate->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="p-3 space-y-2 h-[400px] overflow-y-auto">
                                @forelse($rosters[$date] ?? [] as $roster)
                                    <div @if(auth()->user()->role === 'admin') wire:click="editRoster({{ $roster->id }})" @endif 
                                         class="flex items-center p-2.5 rounded-lg transition-all duration-200
                                         {{ auth()->user()->role === 'admin' ? 'cursor-pointer hover:bg-white hover:shadow-md hover:scale-[1.03] transform' : '' }}">
                                        
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 text-gray-600 flex items-center justify-center font-bold shrink-0">
                                            {{ substr($roster->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3 overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $roster->user->name }}</p>
                                            <span class="text-xs px-2 py-0.5 rounded font-semibold
                                                {{ $roster->shift->is_overnight ? 'bg-slate-800 text-white' : 
                                                  ($roster->shift->name == 'Regu Pagi' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ $roster->shift->name }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="h-full flex items-center justify-center text-gray-400">
                                        <p>Libur / Tidak Ada Jadwal</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                         <div class="w-full flex items-center justify-center text-gray-400 h-64">
                            <p>Tidak ada data jadwal untuk ditampilkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    {{-- ====================================================================== --}}
    {{-- MODAL EDIT JADWAL (Tidak diubah, sudah bagus)
    {{-- ====================================================================== --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         x-data="{ show: @entangle('isModalOpen') }" x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative w-full max-w-md p-4" @click.away="show = false"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <div class="relative bg-white rounded-2xl shadow-2xl border">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-bold text-gray-900">Ubah Jadwal Dinas</h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-900 w-8 h-8 flex justify-center items-center rounded-lg hover:bg-gray-100 transition">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <p class="text-gray-600">Mengubah shift untuk: <span class="font-bold text-indigo-700">{{ $selectedRosterName }}</span></p>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Shift Baru:</label>
                        <select wire:model="selectedShiftId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 transition">
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center p-4 border-t bg-gray-50 rounded-b-2xl">
                    <button wire:click="saveRoster" type="button" class="text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-sm px-5 py-2.5 shadow-md transform transition hover:-translate-y-0.5 w-full">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ====================================================================== --}}
{{-- SCRIPT CHART JS (Sedikit modifikasi untuk warna)
{{-- ====================================================================== --}}
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        const chartElement = document.getElementById('shiftChart');
        if (!chartElement) return;

        const renderChart = () => {
            const stats = @json($shiftStats);
            
            if (window.myShiftChart instanceof Chart) {
                window.myShiftChart.destroy();
            }
            
            window.myShiftChart = new Chart(chartElement, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(stats),
                    datasets: [{
                        data: Object.values(stats),
                        backgroundColor: [
                            'rgba(251, 191, 36, 0.8)', // Kuning Pagi
                            'rgba(96, 165, 250, 0.8)', // Biru Siang
                            'rgba(30, 41, 59, 0.8)',   // Gelap Malam
                        ],
                        borderColor: [
                            '#FBBF24',
                            '#60A5FA',
                            '#1E293B',
                        ],
                        borderWidth: 2,
                        hoverOffset: 12,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%', 
                    plugins: {
                        legend: { display: false },
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 800,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }
        
        renderChart();

        @this.on('roster-updated', () => {
            setTimeout(renderChart, 200);
        });
    });
</script>
@endpush
