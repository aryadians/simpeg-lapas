<div x-data="{ open: false }" class="relative">
    {{-- Trigger Bell --}}
    <button @click="open = !open" class="h-10 w-10 rounded-full bg-gray-50 text-gray-400 hover:text-indigo-600 flex items-center justify-center transition-all relative border border-gray-100 hover:shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 h-4 w-4 bg-rose-500 text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-white animate-bounce">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-3 w-80 bg-white rounded-[1.5rem] shadow-2xl border border-gray-100 overflow-hidden z-[100]" x-cloak>
        
        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Signals</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button wire:click="markAllAsRead" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors">Clear All</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto no-scrollbar">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <div wire:key="{{ $notification->id }}" @click="open = false" class="p-4 border-b border-gray-50 hover:bg-indigo-50/30 transition-colors cursor-pointer group">
                    <div class="flex gap-4">
                        <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            @if(str_contains($notification->type, 'Shift'))
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-bold text-gray-800 leading-tight">{{ $notification->data['message'] ?? 'No detail available' }}</p>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <button wire:click.stop="markAsRead('{{ $notification->id }}')" class="opacity-0 group-hover:opacity-100 text-gray-300 hover:text-indigo-600 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center opacity-30 grayscale">
                    <span class="text-4xl block mb-2">📡</span>
                    <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest">Frequencies Quiet</p>
                </div>
            @endforelse
        </div>

        @if(auth()->user()->notifications->count() > 0)
            <div class="p-4 bg-gray-50/50 text-center">
                <a href="#" class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">Operational Archive</a>
            </div>
        @endif
    </div>
</div>
