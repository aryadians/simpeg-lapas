<div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate__animated animate__fadeInUp">
    <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Active Sessions</h3>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Authorized Access Devices</p>
        </div>
        <button wire:click="logoutOtherDevices" class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-rose-100 hover:bg-rose-600 hover:text-white transition-all">Logout Others</button>
    </div>

    <div class="divide-y divide-gray-50">
        @foreach($this->sessions as $session)
        <div class="p-6 flex items-center justify-between group hover:bg-gray-50/50 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                    @if(str_contains(strtolower($session->user_agent), 'mobile') || str_contains(strtolower($session->user_agent), 'android') || str_contains(strtolower($session->user_agent), 'iphone'))
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    @else
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-black text-gray-800 uppercase tracking-tight">
                        {{ $session->id === session()->getId() ? 'Current Device' : 'Authorized Device' }}
                    </p>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $session->ip_address }} • {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</p>
                </div>
            </div>
            @if($session->id !== session()->getId())
                <button wire:click="logoutSession('{{ $session->id }}')" class="h-8 w-8 rounded-lg bg-gray-50 text-gray-300 hover:text-rose-600 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all border border-transparent hover:border-rose-100"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
            @endif
        </div>
        @endforeach
    </div>
</div>
