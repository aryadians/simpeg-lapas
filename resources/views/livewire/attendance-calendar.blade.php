<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-5xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Activity <span class="text-emerald-600">Journal</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Monthly Attendance & Performance Logs</p>
                </div>

                <div class="flex items-center gap-3 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 shadow-inner relative z-10">
                    <button wire:click="prevMonth" class="h-10 w-10 bg-white rounded-xl shadow-sm hover:bg-gray-50 text-gray-400 flex items-center justify-center transition-all border border-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <div class="px-6 text-xs font-black text-gray-900 uppercase tracking-widest min-w-[150px] text-center">
                        {{ \Carbon\Carbon::create(null, $this->month)->translatedFormat('F') }} {{ $this->year }}
                    </div>
                    <button wire:click="nextMonth" class="h-10 w-10 bg-white rounded-xl shadow-sm hover:bg-gray-50 text-gray-400 flex items-center justify-center transition-all border border-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- CALENDAR GRID --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-8 animate__animated animate__fadeInUp">
            
            {{-- Day Names --}}
            <div class="grid grid-cols-7 gap-4 mb-6">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                    <div class="text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">{{ $dayName }}</div>
                @endforeach
            </div>

            {{-- Day Grid --}}
            <div class="grid grid-cols-7 gap-4">
                @foreach($daysInMonth as $dayData)
                    <div class="aspect-square relative group">
                        @if($dayData)
                            <div class="absolute inset-0 rounded-2xl border-2 transition-all duration-500 overflow-hidden 
                                @if(!$dayData['attendance']) border-gray-50 bg-gray-50/30 
                                @elseif($dayData['attendance']->status === 'hadir') border-emerald-100 bg-emerald-50/50 group-hover:border-emerald-500
                                @elseif($dayData['attendance']->status === 'terlambat') border-amber-100 bg-amber-50/50 group-hover:border-amber-500
                                @else border-rose-100 bg-rose-50/50 group-hover:border-rose-500 @endif
                             flex flex-col items-center justify-center">
                                
                                <span class="text-xs font-black @if($dayData['attendance']) text-gray-900 @else text-gray-300 @endif">{{ $dayData['day'] }}</span>
                                
                                @if($dayData['attendance'])
                                    <div class="mt-1 flex flex-col items-center">
                                        <p class="text-[8px] font-black uppercase tracking-widest 
                                            {{ $dayData['attendance']->status === 'hadir' ? 'text-emerald-600' : ($dayData['attendance']->status === 'terlambat' ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ $dayData['attendance']->clock_in ? \Carbon\Carbon::parse($dayData['attendance']->clock_in)->format('H:i') : $dayData['attendance']->status }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- LEGEND --}}
            <div class="mt-12 pt-8 border-t border-gray-50 flex flex-wrap justify-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">On Time Presence</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-2.5 w-2.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.5)]"></div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Late Arrival</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-2.5 w-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Non Compliance</span>
                </div>
            </div>
        </div>
    </div>
</div>
