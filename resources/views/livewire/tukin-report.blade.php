<div class="p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col xl:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center xl:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Payroll <span class="text-indigo-600">Analytics</span></h1>
                    <div class="flex items-center justify-center xl:justify-start gap-2 mt-1">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Statement Period:</span>
                        <span class="text-xs font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100">{{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 relative z-10">
                    <div class="flex bg-gray-50 p-1 rounded-2xl border border-gray-200">
                        <input type="month" wire:model.lazy="selectedMonth" class="bg-white border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2 shadow-sm">
                    </div>
                    
                    <button wire:click="generateReport" wire:loading.attr="disabled" class="h-11 px-5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                        <span wire:loading.remove wire:target="generateReport">Sync Intelligence</span>
                        <span wire:loading wire:target="generateReport">Analyzing...</span>
                    </button>

                    <div class="h-8 w-px bg-gray-200 mx-1"></div>

                    <button onclick="window.open('{{ route('tukin.report.pdf', ['month' => $selectedMonth]) }}', '_blank')" class="h-11 px-5 rounded-2xl bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 shadow-sm transition-all active:scale-95 flex items-center gap-2 font-black text-[10px] uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </header>

        {{-- Employee Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 animate__animated animate__fadeInUp">
            @forelse ($reportData as $userReport)
                <div class="bg-white rounded-[2.5rem] shadow-lg border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:border-indigo-200 group relative overflow-hidden flex flex-col">
                    {{-- Card Header --}}
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex justify-between items-start gap-4">
                            <div class="min-w-0">
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight truncate group-hover:text-indigo-600 transition-colors">{{ $userReport['name'] }}</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">{{ $userReport['jabatan'] }}</p>
                                <p class="text-[9px] font-bold text-gray-300 font-mono mt-1">ID: {{ $userReport['nip'] }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Grade</p>
                                <p class="text-2xl font-black text-indigo-600 tracking-tighter">{{ $userReport['grade'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Financials --}}
                    <div class="p-8 flex-1">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Base Allocation</span>
                                <span class="font-black text-gray-800 text-sm">Rp {{ number_format($userReport['tukin_nominal'], 0, ',', '.') }}</span>
                            </div>
                             <div class="flex justify-between items-center bg-rose-50/30 p-4 rounded-2xl border border-rose-100">
                                <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Performance Penalty ({{ $userReport['total_deduction_percentage'] }}%)</span>
                                <span class="font-black text-rose-600 text-sm">- Rp {{ number_format($userReport['total_deduction_amount'], 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="pt-4 mt-4 border-t-4 border-double border-indigo-50 flex justify-between items-center">
                                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">Net Payout</span>
                                <span class="font-black text-2xl text-indigo-600 tracking-tighter">Rp {{ number_format($userReport['final_tukin'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Details Accordion --}}
                    <div x-data="{ open: false }" class="border-t border-gray-50">
                        <button @click="open = !open" class="w-full px-8 py-4 flex justify-between items-center bg-white hover:bg-gray-50 transition-colors group/btn">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover/btn:text-indigo-500 transition-colors">Incident Audit Logs</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-300 group-hover/btn:text-indigo-500 transform transition-all duration-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="bg-gray-50/50">
                            <div class="px-8 pb-8 pt-2">
                                @php $hasDeductions = collect($userReport['attendances'])->where('deduction', '>', 0)->isNotEmpty(); @endphp
                                @if($hasDeductions)
                                <table class="w-full text-left border-separate border-spacing-y-2">
                                    <thead>
                                        <tr class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">
                                            <th class="px-3">Date</th>
                                            <th class="px-3 text-center">Protocol</th>
                                            <th class="px-3 text-right">Impact</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userReport['attendances'] as $att)
                                            @if($att['deduction'] > 0)
                                            <tr class="bg-white rounded-xl shadow-sm border border-gray-100 group/row overflow-hidden">
                                                <td class="px-3 py-2 text-[10px] font-bold text-gray-500 font-mono">{{ $att['date'] }}</td>
                                                <td class="px-3 py-2 text-center">
                                                    @if ($att['status'] == 'terlambat')
                                                        <span class="text-[9px] font-black uppercase text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">DELAY</span>
                                                    @elseif ($att['status'] == 'alpha')
                                                        <span class="text-[9px] font-black uppercase text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100">ABSENT</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-right text-[10px] font-black text-rose-500">-{{ $att['deduction'] }}%</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <div class="py-10 text-center opacity-30 grayscale">
                                        <span class="text-3xl mb-2 block">🛡️</span>
                                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest">No Violations Recorded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <div class="flex flex-col items-center justify-center opacity-30 grayscale">
                        <span class="text-7xl mb-6">💰</span>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">Payroll Data Empty</h3>
                        <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em]">Register salary assets in personnel management.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
