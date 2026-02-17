<div>
    {{-- Panic Button --}}
    <button onclick="Livewire.dispatch('confirm-dialog', { title: 'AKTIFKAN PANIC BUTTON?', text: 'Ini akan mengirim sinyal darurat ke seluruh sistem!', confirm_event: 'trigger-panic', confirm_params: {} })" 
            class="h-10 w-10 sm:w-auto sm:px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-lg shadow-rose-500/40 transition-all active:scale-90 flex items-center justify-center gap-2 font-black text-[10px] uppercase tracking-widest animate-pulse">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
        <span class="hidden sm:inline">Panic</span>
    </button>

    {{-- Alert Overlay --}}
    @if($activeAlert)
    <div class="fixed inset-0 z-[200] pointer-events-none">
        <div class="absolute inset-0 bg-rose-600/20 animate-pulse border-[20px] border-rose-600/50"></div>
        <div class="absolute top-24 left-1/2 -translate-x-1/2 w-full max-w-lg p-6 pointer-events-auto">
            <div class="bg-white rounded-[2rem] shadow-2xl border-4 border-rose-600 p-8 text-center animate__animated animate__headShake animate__infinite">
                <span class="text-6xl block mb-4">🚨</span>
                <h2 class="text-3xl font-black text-rose-600 uppercase tracking-tighter">Emergency Active</h2>
                <p class="text-gray-600 font-bold mt-2 uppercase tracking-widest text-sm">Triggered by: <span class="text-black">{{ $activeAlert['user'] }}</span></p>
                <p class="text-xs text-gray-400 font-mono mt-1">{{ $activeAlert['time'] }}</p>
                
                <button wire:click="dismissAlert" class="mt-8 px-8 py-3 bg-rose-600 text-white font-black rounded-xl uppercase tracking-[0.2em] text-xs shadow-lg hover:bg-rose-700 transition-all">Dismiss Alert</button>
            </div>
        </div>
    </div>
    @endif
</div>
