<div>
    {{-- Panic Button --}}
    <button @click="$dispatch('confirm-dialog', { title: 'AKTIFKAN PANIC BUTTON?', text: 'Ini akan mengirim sinyal darurat ke seluruh sistem!', confirm_event: 'trigger-panic', confirm_params: {} })" 
            class="h-10 w-10 md:w-auto md:px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-lg shadow-rose-500/40 transition-all active:scale-90 flex items-center justify-center gap-2 font-black text-[10px] uppercase tracking-widest animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
        <span class="hidden sm:inline">Panic</span>
    </button>

    {{-- Alert Overlay (Centered) --}}
    @if($activeAlert)
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 pointer-events-none">
        {{-- Pulsing Background --}}
        <div class="absolute inset-0 bg-rose-600/30 animate-pulse border-[10px] md:border-[30px] border-rose-600/50"></div>
        
        {{-- Center Card --}}
        <div class="relative w-full max-w-lg pointer-events-auto">
            <div class="bg-white rounded-[3rem] shadow-[0_0_100px_rgba(225,29,72,0.5)] border-4 border-rose-600 p-8 md:p-12 text-center animate__animated animate__headShake animate__infinite">
                <div class="h-24 w-24 bg-rose-50 text-rose-600 rounded-full mx-auto flex items-center justify-center mb-6 shadow-inner border-2 border-rose-100">
                    <span class="text-6xl">🚨</span>
                </div>
                
                <h2 class="text-3xl md:text-4xl font-black text-rose-600 uppercase tracking-tighter leading-none">Emergency<br>Active</h2>
                
                <div class="mt-6 space-y-1">
                    <p class="text-gray-400 font-black uppercase tracking-[0.2em] text-[10px]">Security Breach Authorized By</p>
                    <p class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $activeAlert['user'] }}</p>
                </div>
                
                <div class="mt-4 inline-block px-4 py-1.5 bg-gray-900 rounded-lg">
                    <p class="text-xs font-bold text-rose-500 font-mono tracking-widest uppercase">{{ $activeAlert['time'] }}</p>
                </div>
                
                <button wire:click="dismissAlert" class="mt-10 w-full py-5 bg-rose-600 text-white font-black rounded-2xl uppercase tracking-[0.2em] text-xs shadow-xl hover:bg-rose-700 transition-all active:scale-95 border-b-4 border-rose-800">
                    Deactivate Alert
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
