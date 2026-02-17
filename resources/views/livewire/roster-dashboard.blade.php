<div class="p-4 sm:p-6 md:p-8 font-sans antialiased" style="perspective: 1000px;">

    {{-- ====================================================================== --}}
    {{-- HEADER: JUDUL, NAVIGASI & AKSI
    {{-- ====================================================================== --}}
    <header class="max-w-7xl mx-auto mb-10 animate-fade-in-down">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6 p-6 bg-white rounded-3xl shadow-xl border border-gray-100 relative overflow-hidden">
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
            
            <div class="relative z-10 text-center lg:text-left">
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Roster <span class="text-indigo-600">Engine</span></h1>
                <div class="flex items-center justify-center lg:justify-start gap-2 mt-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Periode Operasional:</span>
                    <span class="text-xs font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('F Y') }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 relative z-10">
                <div class="flex bg-gray-100 p-1 rounded-2xl border border-gray-200">
                    <button wire:click="prevDays" class="p-2.5 h-11 w-11 rounded-xl bg-white hover:bg-indigo-50 text-indigo-600 shadow-sm transition-all active:scale-95 flex items-center justify-center border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button wire:click="nextDays" class="p-2.5 h-11 w-11 rounded-xl bg-white hover:bg-indigo-50 text-indigo-600 shadow-sm transition-all active:scale-95 flex items-center justify-center border border-gray-200 ml-1">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
                
                <a href="/cetak-laporan" target="_blank" class="h-11 px-5 rounded-2xl bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 shadow-sm transition-all active:scale-95 flex items-center gap-2 font-black text-[10px] uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Export
                </a>
                
                @if(strtolower(trim(auth()->user()->role)) === 'admin')
                <div class="h-8 w-px bg-gray-200 mx-1"></div>
                <button wire:click="create" class="h-11 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/30 transition-all active:scale-95 flex items-center gap-2 font-black text-[10px] uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Manual
                </button>
                <button onclick="Livewire.dispatch('confirm-dialog', { title: 'Generate Baru?', text: 'Data bulan ini akan di-reset.', confirm_event: 'generate-schedule-confirmed', confirm_params: {} })"
                        class="h-11 px-6 rounded-2xl bg-black hover:bg-gray-900 text-white shadow-xl transition-all active:scale-95 flex items-center gap-2 font-black text-[10px] uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Auto Generate
                </button>
                @endif
            </div>
        </div>
    </header>

    {{-- ====================================================================== --}}
    {{-- KONTEN UTAMA: WIDGET & STATISTIK
    {{-- ====================================================================== --}}
    <main class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- KOLOM KIRI: WIDGET ABSEN & CHART --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="animate-pop-in">
                <livewire:attendance-widget :todayRoster="$todaysRosterForUser" />
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 animate-pop-in" style="animation-delay: 100ms;">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 border-b border-gray-50 pb-3">Operational Load</h3>
                <div class="h-48 w-full flex justify-center items-center">
                    <canvas id="shiftChart"></canvas>
                </div>
                 <div class="mt-6 grid grid-cols-1 gap-2">
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-500 bg-gray-50 p-2 rounded-xl"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Pagi</span> <span>Regu 1</span></div>
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-500 bg-gray-50 p-2 rounded-xl"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-400"></span> Siang</span> <span>Regu 2</span></div>
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-gray-500 bg-gray-50 p-2 rounded-xl"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-slate-800"></span> Malam</span> <span>Regu 3</span></div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STATISTIK & JADWAL --}}
        <div class="lg:col-span-3 space-y-8">
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-indigo-600 text-white rounded-3xl p-5 shadow-xl shadow-indigo-500/20 animate-pop-in" style="animation-delay: 200ms;">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Staff</p>
                    <h3 class="text-3xl font-black mt-1 leading-none">{{ $todayStats['total_pegawai'] ?? 0 }}</h3>
                </div>
                <div class="bg-emerald-500 text-white rounded-3xl p-5 shadow-xl shadow-emerald-500/20 animate-pop-in" style="animation-delay: 300ms;">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Present</p>
                    <h3 class="text-3xl font-black mt-1 leading-none">{{ $todayStats['hadir_hari_ini'] ?? 0 }}</h3>
                </div>
                 <div class="bg-amber-500 text-white rounded-3xl p-5 shadow-xl shadow-amber-500/20 animate-pop-in" style="animation-delay: 400ms;">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Leave</p>
                    <h3 class="text-3xl font-black mt-1 leading-none">{{ $todayStats['cuti_hari_ini'] ?? 0 }}</h3>
                </div>
                <div class="bg-rose-500 text-white rounded-3xl p-5 shadow-xl shadow-rose-500/20 animate-pop-in" style="animation-delay: 500ms;">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Alpha</p>
                    <h3 class="text-3xl font-black mt-1 leading-none">{{ $todayStats['alpha_hari_ini'] ?? 0 }}</h3>
                </div>
            </div>

            {{-- Jadwal Harian --}}
            <div class="bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 p-2 animate-fade-in-up" style="animation-delay: 400ms;">
                <div class="flex gap-4 overflow-x-auto p-4 custom-scrollbar no-scrollbar">
                    @forelse($dateRange as $date)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($date);
                            $isToday = $carbonDate->isToday();
                        @endphp
                        <div class="flex-shrink-0 w-72 bg-gray-50/50 rounded-[2rem] border-2 {{ $isToday ? 'border-indigo-500' : 'border-transparent' }} transition-all duration-300 overflow-hidden">
                            <div class="p-6 {{ $isToday ? 'bg-indigo-600 text-white' : 'bg-white text-gray-900 border-b border-gray-100' }}">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] {{ $isToday ? 'text-indigo-200' : 'text-gray-400' }}">{{ $carbonDate->translatedFormat('l') }}</p>
                                <p class="text-2xl font-black tracking-tighter mt-1">{{ $carbonDate->translatedFormat('d M') }}</p>
                            </div>
                            <div class="p-4 space-y-3 h-[420px] overflow-y-auto no-scrollbar">
                                @forelse($rosters[$date] ?? [] as $roster)
                                    <div wire:key="{{ $roster->id }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100/80 transition-all duration-300 hover:shadow-md hover:border-indigo-200 group relative">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 text-gray-500 flex items-center justify-center font-black text-xs shadow-inner shrink-0 group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white transition-all duration-500">
                                                {{ strtoupper(substr($roster->user->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-black text-gray-800 truncate uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $roster->user->name }}</p>
                                                <div class="flex items-center gap-1.5 mt-1">
                                                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ $roster->shift->is_overnight ? 'bg-slate-800' : ($roster->shift->name == 'Regu Pagi' ? 'bg-yellow-400' : 'bg-blue-400') }}"></span>
                                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $roster->shift->name }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Action Buttons for Admin --}}
                                        @if(strtolower(trim(auth()->user()->role)) === 'admin')
                                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 backdrop-blur-sm p-1 rounded-lg">
                                            <button wire:click="editRoster({{ $roster->id }})" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-md transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z" /></svg></button>
                                            <button wire:click="delete({{ $roster->id }})" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-md transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                        </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center text-gray-300 py-10">
                                        <span class="text-4xl mb-3 grayscale opacity-30">🏝️</span>
                                        <p class="text-[9px] font-black uppercase tracking-[0.3em]">Standby Status</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                         <div class="w-full flex items-center justify-center text-gray-400 h-64">
                            <p class="text-[10px] font-black uppercase tracking-widest">No Operational Data</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    {{-- ====================================================================== --}}
    {{-- MODAL CRUD JADWAL
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

            <div class="relative bg-white rounded-[2rem] shadow-2xl border border-white/20 overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">
                        {{ $isEditMode ? 'Modify Entry' : 'New Assignment' }}
                    </h3>
                    <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-900 w-10 h-10 flex justify-center items-center rounded-xl hover:bg-gray-100 transition-all">✕</button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="p-8 space-y-6">
                        
                        {{-- User Selection --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Personnel</label>
                            @if($isEditMode)
                                <input type="text" value="{{ $rosterUserName }}" class="bg-gray-100 border-none text-gray-900 text-sm rounded-2xl block w-full p-4 font-bold" disabled>
                            @else
                                <select wire:model.live="userId" class="bg-gray-50 border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block w-full p-4 transition font-bold">
                                    <option value="">Select Personnel</option>
                                    @foreach($allUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('userId') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 px-1 block">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        {{-- Date Selection --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Operation Date</label>
                            <input type="date" wire:model.live="date" class="bg-gray-50 border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block w-full p-4 transition font-bold" {{ $isEditMode ? 'disabled' : '' }}>
                            @error('date') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 px-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Shift Selection --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Duty Shift</label>
                            <select wire:model.live="shiftId" class="bg-gray-50 border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block w-full p-4 transition font-bold">
                                <option value="">Select Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">
                                        {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('shiftId') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest mt-1 px-1 block">{{ $message }}</span> @enderror
                        </div>

                    </div>
                    <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex items-center">
                        <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-black rounded-2xl text-xs px-5 py-4 shadow-lg shadow-indigo-500/20 transform transition active:scale-95 w-full uppercase tracking-[0.2em]">
                            {{ $isEditMode ? 'Save Changes' : 'Confirm Assignment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ====================================================================== --}}
{{-- SCRIPT CHART JS
{{-- ====================================================================== --}}
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Chart DOUGHNUT untuk Komposisi Shift
        const shiftChartElement = document.getElementById('shiftChart');
        if (shiftChartElement) {
            const renderShiftChart = () => {
                const stats = @json($shiftStats);
                const labels = Object.keys(stats);
                const values = Object.values(stats);
                
                // Color mapping based on name
                const colorMap = {
                    'Regu Pagi': 'rgba(251, 191, 36, 0.8)',
                    'Regu Siang': 'rgba(96, 165, 250, 0.8)',
                    'Regu Malam': 'rgba(30, 41, 59, 0.8)'
                };
                const borderMap = {
                    'Regu Pagi': '#FBBF24',
                    'Regu Siang': '#60A5FA',
                    'Regu Malam': '#1E293B'
                };

                const backgroundColors = labels.map(label => colorMap[label] || '#cbd5e1');
                const borderColors = labels.map(label => borderMap[label] || '#94a3b8');

                if (window.myShiftChart instanceof Chart) {
                    window.myShiftChart.destroy();
                }
                
                window.myShiftChart = new Chart(shiftChartElement, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 2,
                            hoverOffset: 12,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '82%',
                        plugins: { legend: { display: false } },
                        animation: { animateScale: true, animateRotate: true, duration: 1000, easing: 'easeInOutQuart' }
                    }
                });
            }
            renderShiftChart();
            @this.on('roster-updated', () => setTimeout(renderShiftChart, 200));
        }

        // Chart DOUGHNUT untuk Statistik Kehadiran Hari Ini
        const attendanceChartElement = document.getElementById('attendanceChart');
        if (attendanceChartElement) {
            const renderAttendanceChart = () => {
                const stats = @json($todayStats);
                const chartData = {
                    'Hadir': stats.hadir_hari_ini,
                    'Cuti': stats.cuti_hari_ini,
                    'Alpha': stats.alpha_hari_ini,
                };

                if (window.myAttendanceChart instanceof Chart) {
                    window.myAttendanceChart.destroy();
                }

                window.myAttendanceChart = new Chart(attendanceChartElement, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(chartData),
                        datasets: [{
                            data: Object.values(chartData),
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',  // Emerald
                                'rgba(245, 158, 11, 0.8)',   // Amber
                                'rgba(244, 63, 94, 0.8)',    // Rose
                            ],
                            borderColor: ['#10B981', '#F59E0B', '#F43F5E'],
                            borderWidth: 2,
                            hoverOffset: 12,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '82%',
                        plugins: { legend: { display: false } },
                        animation: { animateScale: true, animateRotate: true, duration: 1000, easing: 'easeInOutQuart' }
                    }
                });
            }
            renderAttendanceChart();
            @this.on('roster-updated', () => setTimeout(renderAttendanceChart, 200));
        }
    });
</script>
@endpush
