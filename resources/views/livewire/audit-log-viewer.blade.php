<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-slate-100 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Audit <span class="text-indigo-600">Trail</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Operational Activity & Integrity Log</p>
                </div>
                
                <div class="relative group w-full md:w-72 z-10">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Logs..." class="pl-10 pr-6 py-3 w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 shadow-inner transition-all duration-300 font-bold text-xs uppercase tracking-widest">
                </div>
            </div>
        </header>

        {{-- TABLE LOGS --}}
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate__animated animate__fadeInUp">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        <tr>
                            <th class="py-6 px-8">Timestamp</th>
                            <th class="py-6 px-8">Operator</th>
                            <th class="py-6 px-8">Event</th>
                            <th class="py-6 px-8">Object Type</th>
                            <th class="py-6 px-8">Source IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-medium">
                        @forelse($logs as $log)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="py-6 px-8 font-mono text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                {{ $log->created_at->format('Y-m-d H:i:s.v') }}
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center font-black text-[10px] text-gray-500 shadow-inner">
                                        {{ substr($log->user->name ?? 'SYS', 0, 1) }}
                                    </div>
                                    <span class="text-xs font-black text-gray-700 uppercase tracking-tight">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <span class="px-2.5 py-1 text-[9px] font-black rounded-md border 
                                    @if($log->event === 'created') bg-emerald-50 text-emerald-700 border-emerald-100
                                    @elseif($log->event === 'updated') bg-amber-50 text-amber-700 border-amber-100
                                    @elseif($log->event === 'deleted') bg-rose-50 text-rose-700 border-rose-100
                                    @else bg-slate-50 text-slate-700 border-slate-100 @endif
                                uppercase tracking-widest shadow-sm">
                                    {{ $log->event }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ class_basename($log->auditable_type) }}</p>
                                <p class="text-[9px] font-bold text-gray-300 font-mono mt-0.5">UID: {{ $log->auditable_id }}</p>
                            </td>
                            <td class="py-6 px-8">
                                <span class="text-[10px] font-bold text-gray-400 font-mono">{{ $log->ip_address }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center opacity-30 grayscale">
                                <span class="text-6xl mb-4 block">📡</span>
                                <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">No Activity Detected</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-gray-50/30 border-t border-gray-50">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
