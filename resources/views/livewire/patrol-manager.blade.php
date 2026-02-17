<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Patrol <span class="text-emerald-600">Checkpoint</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Sistem Verifikasi Patroli Keamanan</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Scan Section --}}
            <div class="space-y-8">
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8">
                    <div class="text-center mb-8">
                        <div class="h-20 w-20 bg-emerald-50 text-emerald-600 rounded-3xl mx-auto flex items-center justify-center shadow-inner mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Scan Identity</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Point device to location tag</p>
                    </div>

                    <form wire:submit.prevent="scanCheckpoint" class="space-y-4">
                        <div class="relative">
                            <input wire:model="scannedCode" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 pl-12 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition font-bold font-mono text-center tracking-widest" placeholder="LOC-TAG-XXX">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2h1v1h-1V5z" clip-rule="evenodd" /><path d="M11 13a1 1 0 011-1h1v1h2v1h-1v1h1v1h-2v-1h-1v1h-1v-2h1v-1h-1v-1z" /><path d="M7 16v3M3 9h3M13 7h3m-4 8v3" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-4 bg-black text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">Verify Location</button>
                    </form>

                    @if($currentCheckpoint)
                    <div class="mt-8 pt-8 border-t border-gray-50 animate__animated animate__fadeIn">
                        <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 mb-6">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Active Checkpoint</p>
                            <h4 class="text-xl font-black text-gray-900 uppercase mt-1">{{ $currentCheckpoint->name }}</h4>
                            <p class="text-xs font-bold text-emerald-700/60 mt-1 uppercase tracking-widest">{{ $currentCheckpoint->location_code }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <textarea wire:model="notes" rows="3" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-xs font-medium resize-none" placeholder="Observation notes (optional)..."></textarea>
                            <button wire:click="submitPatrol" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">Log Patrol Data</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recent History --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 overflow-hidden relative">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Daily Activity</h3>
                    <a href="{{ route('patrol.report.pdf', ['month' => now()->format('Y-m')]) }}" target="_blank" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 hover:bg-indigo-600 hover:text-white transition-all">Export PDF</a>
                </div>
                
                <div class="space-y-6 relative">
                    <div class="absolute left-6 top-0 bottom-0 w-px bg-gray-100"></div>
                    
                    @forelse($recentPatrols as $patrol)
                    <div class="flex gap-6 relative group">
                        <div class="h-12 w-12 rounded-2xl bg-white border-2 border-gray-50 shadow-sm flex items-center justify-center relative z-10 text-emerald-500 group-hover:border-emerald-500 transition-all duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $patrol->checkpoint->name }}</p>
                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-0.5">{{ $patrol->user->name }} • <span class="text-gray-400">{{ $patrol->created_at->format('H:i') }}</span></p>
                            @if($patrol->notes)
                                <p class="text-[10px] text-gray-500 font-medium mt-2 bg-gray-50 p-2 rounded-lg border border-gray-100 italic">"{{ $patrol->notes }}"</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-10 text-center opacity-20 grayscale">
                        <span class="text-4xl block mb-2">📡</span>
                        <p class="text-[10px] font-black uppercase tracking-widest">Scanning Perimeter...</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
