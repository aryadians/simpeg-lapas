<div class="p-6 bg-gray-50 min-h-screen font-sans pb-20">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Admin <span class="text-indigo-600">Console</span></h1>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Real-time System Intelligence & Control</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="px-6 py-3 bg-gray-50 rounded-2xl border border-gray-100 text-center shadow-inner">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Postings</p>
                            <p class="text-xl font-black text-indigo-600 leading-none mt-1">{{ $onDutyToday }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            
            {{-- Stat Cards Column --}}
            <div class="xl:col-span-3 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate__animated animate__fadeInUp">
                    {{-- Active Personnel --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-indigo-200 transition-all duration-500 group">
                        <div class="h-14 w-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Staff</p>
                            <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $totalEmployees }}</p>
                        </div>
                    </div>

                    {{-- Patrols --}}
                    <a href="{{ route('patrol') }}" class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-emerald-200 transition-all duration-500 group">
                        <div class="h-14 w-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                             <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 5.04 12.07 12.07 0 001.477 11.125A12.027 12.027 0 0012 21c3.539 0 6.675-1.533 8.859-3.978a12.07 12.07 0 001.477-11.125z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Patrols Today</p>
                            <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $patrolsToday }}</p>
                        </div>
                    </a>

                    {{-- Swap Requests --}}
                    <a href="{{ route('shift.exchange') }}" class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 flex items-center gap-6 hover:shadow-2xl hover:border-amber-200 transition-all duration-500 group relative overflow-hidden">
                        @if($pendingSwaps > 0)
                            <div class="absolute top-0 right-0 h-2 w-full bg-amber-500 animate-pulse"></div>
                        @endif
                        <div class="h-14 w-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending Swaps</p>
                            <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $pendingSwaps }}</p>
                        </div>
                    </a>
                </div>

                {{-- Action Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- ... (existing alerts and leave queue) ... --}}
                </div>

                {{-- ANALYTICS CHART SECTION --}}
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter mb-6">Attendance Intelligence (7 Days)</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="adminTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity Log Column --}}
            <div class="xl:col-span-1">
                <div class="bg-gray-900 rounded-[2.5rem] shadow-2xl p-8 sticky top-24 border-t-4 border-indigo-500">
                    <h3 class="text-lg font-black text-white uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Signals
                    </h3>
                    
                    <div class="space-y-8">
                        @foreach($recentAuditLogs as $log)
                        <div class="flex gap-4 relative group">
                            @if(!$loop->last)
                                <div class="absolute left-4 top-10 bottom-[-2rem] w-px bg-white/10"></div>
                            @endif
                            <div class="h-8 w-8 rounded-lg bg-white/10 flex items-center justify-center font-black text-[10px] text-white/40 shrink-0 relative z-10 border border-white/5">
                                {{ substr($log->user->name ?? 'SYS', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-white uppercase tracking-tight truncate">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mt-0.5">{{ $log->event }} on {{ class_basename($log->auditable_type) }}</p>
                                <p class="text-[8px] font-bold text-white/20 font-mono mt-1 uppercase">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('audit.logs') }}" class="mt-12 block w-full py-4 bg-white/5 hover:bg-white/10 text-white rounded-2xl text-center text-[10px] font-black uppercase tracking-[0.3em] transition-all border border-white/5">View Full Archive</a>
                </div>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        const ctx = document.getElementById('adminTrendChart');
        
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($attendanceTrendLabels),
                    datasets: [
                        {
                            label: 'On-Time Presence',
                            data: @json($attendanceTrendData),
                            borderColor: '#10B981', // Emerald 500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10B981',
                            pointRadius: 4,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Late Arrivals',
                            data: @json($lateTrendData),
                            borderColor: '#F59E0B', // Amber 500
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#F59E0B',
                            pointRadius: 4,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { family: "'Inter', sans-serif", size: 11, weight: 'bold' }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, borderDash: [5, 5], color: '#f3f4f6' },
                            ticks: { font: { family: "'Inter', sans-serif", size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif", size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
