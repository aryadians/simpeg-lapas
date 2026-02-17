<div class="bg-gray-900 rounded-[2rem] p-8 border-t-4 border-indigo-500 shadow-2xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h3 class="text-xl font-black text-white uppercase tracking-tighter">System <span class="text-indigo-400">Vault</span></h3>
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Institutional Data Integrity & Cold Storage</p>
        </div>
        
        <button wire:click="generateBackup" 
                wire:loading.attr="disabled"
                class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 transform transition active:scale-95 flex items-center gap-3 uppercase tracking-[0.2em] text-[10px]">
            <span wire:loading.remove wire:target="generateBackup">Execute Backup</span>
            <span wire:loading wire:target="generateBackup">Encrypting Archive...</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
        </button>
    </div>
</div>
