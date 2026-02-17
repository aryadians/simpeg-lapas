<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-sky-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Duty <span class="text-sky-600">Logs</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Monitoring Situasi & Serah Terima Regu</p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 relative z-10">
                    <div class="px-5 py-3 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-3 shadow-inner">
                        <div class="h-2 w-2 rounded-full {{ str_contains($shift_name, 'Malam') ? 'bg-indigo-500' : (str_contains($shift_name, 'Pagi') ? 'bg-amber-500' : 'bg-sky-500') }}"></div>
                        <span class="font-black text-gray-700 text-[10px] uppercase tracking-widest">{{ $shift_name }}</span>
                    </div>
                    
                    <button wire:click.prevent="showCreateForm" type="button" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-black rounded-2xl shadow-lg shadow-sky-500/30 transform transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" stroke-width="1"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                        <span>New Entry</span>
                    </button>
                </div>
            </div>
        </header>

        {{-- FORM MODAL --}}
        @if($showForm)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div wire:click="cancel" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>

            <form wire:submit.prevent="submitLog" class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster border border-white/20">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center">Duty <span class="text-sky-600">Statement</span></h3>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Current Shift</label>
                            <select wire:model="shift_name" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition font-bold uppercase tracking-widest text-xs">
                                <option>Regu Pagi</option>
                                <option>Regu Siang</option>
                                <option>Regu Malam</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Inmate Census (WBP)</label>
                            <input type="number" wire:model.lazy="wbp_count" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition font-bold font-mono text-center text-lg" placeholder="0">
                            @error('wbp_count') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block text-center">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Narrative Situation</label>
                        <textarea wire:model.lazy="description" rows="5" placeholder="Report operational situation..." class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-[2rem] p-6 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition resize-none font-medium text-sm leading-relaxed"></textarea>
                        @error('description') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-4 p-5 rounded-[1.5rem] cursor-pointer transition-all duration-500 group border-2 border-transparent"
                            :class="{ 'bg-rose-50 border-rose-100 shadow-lg shadow-rose-500/10': $wire.is_urgent, 'bg-gray-50/50 hover:bg-gray-100': !$wire.is_urgent }"
                        >
                            <input type="checkbox" wire:model="is_urgent" class="h-6 w-6 rounded-lg border-gray-300 text-rose-600 focus:ring-rose-500 transition-transform" :class="{ 'scale-110': $wire.is_urgent }">
                            <div class="flex-grow min-w-0">
                                <span class="font-black text-[10px] uppercase tracking-widest transition-colors" :class="{ 'text-rose-700': $wire.is_urgent, 'text-gray-500': !$wire.is_urgent }">Urgency Override</span>
                                <p class="text-[9px] font-bold uppercase opacity-60 tracking-widest mt-0.5" :class="{ 'text-rose-600': $wire.is_urgent, 'text-gray-400': !$wire.is_urgent }">Flag as immediate priority</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center gap-3">
                    <button wire:click.prevent="cancel" type="button" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Dismiss</button>
                    <button type="submit" class="flex-[2] py-4 text-white bg-sky-600 hover:bg-sky-700 rounded-2xl font-black shadow-lg shadow-sky-500/30 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">
                        <span wire:loading.remove wire:target="submitLog">Commit Log</span>
                        <span wire:loading wire:target="submitLog"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- TIMELINE --}}
        <div class="space-y-12 animate__animated animate__fadeInUp relative pb-20">
            {{-- Global Timeline Line --}}
            <div class="absolute left-8 sm:left-10 top-0 bottom-0 w-px bg-gradient-to-b from-gray-200 via-gray-200 to-transparent"></div>

            @forelse($logs as $log)
            <div wire:key="{{ $log->id }}" class="group relative flex items-start gap-x-6 sm:gap-x-10">
                <!-- Avatar & Status -->
                <div class="shrink-0 flex flex-col items-center relative z-10 mt-2">
                    <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-[1.5rem] bg-white border-4 border-gray-50 shadow-xl flex items-center justify-center font-black text-gray-400 text-xl group-hover:border-sky-500 group-hover:text-sky-600 transition-all duration-500 overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-white group-hover:from-sky-50 group-hover:to-white transition-colors duration-500"></div>
                        <span class="relative">{{ strtoupper(substr($log->user->name, 0, 2)) }}</span>
                    </div>
                    @if($log->is_urgent)
                        <div class="absolute -top-2 -right-2 h-6 w-6 bg-rose-600 rounded-full flex items-center justify-center text-white shadow-lg animate-pulse border-2 border-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        </div>
                    @endif
                </div>

                <!-- Log Card -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:border-sky-200 group/card relative overflow-hidden">
                        @if($log->is_urgent)
                            <div class="absolute top-0 right-0 px-6 py-2 bg-rose-600 text-white font-black text-[9px] uppercase tracking-[0.3em] rounded-bl-2xl shadow-lg">Immediate Priority</div>
                        @endif

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                            <div class="space-y-1.5">
                                <h4 class="font-black text-gray-900 text-lg uppercase tracking-tight">{{ $log->user->name }}</h4>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ 
                                        str_contains($log->shift_name, 'Malam') ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                                        (str_contains($log->shift_name, 'Pagi') ? 'bg-amber-50 text-amber-700 border-amber-100' : 
                                        'bg-sky-50 text-sky-700 border-sky-100') 
                                    }}">{{ $log->shift_name }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 font-mono uppercase tracking-widest">{{ $log->created_at->format('H:i') }} | {{ $log->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            
                            @if(auth()->user()->role === 'admin' || auth()->id() === $log->user_id)
                            <button wire:click="deleteLog({{ $log->id }})" class="h-10 w-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100 active:scale-95" title="Archive Record">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                            @endif
                        </div>

                        <div class="relative">
                            <div class="p-6 bg-gray-50/50 rounded-[1.5rem] border border-gray-100 font-medium text-gray-700 leading-relaxed text-sm whitespace-pre-wrap mb-6 group-hover/card:bg-white transition-colors duration-500">
                                {{ $log->description }}
                            </div>
                            
                            <div class="flex items-center justify-between border-t border-gray-50 pt-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Census Authority</span>
                                    <span class="h-px w-8 bg-gray-100"></span>
                                    <span class="text-sm font-black text-sky-600 uppercase tracking-tighter">{{ $log->wbp_count }} WBP</span>
                                </div>
                                <div class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]" title="Signed & Verified"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                <span class="text-7xl mb-6 grayscale opacity-20">📖</span>
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">Archive Empty</h3>
                <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em] text-gray-400">Be the first to record operational status.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
