<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans" x-data="{ 
    showDetail: false, 
    selectedLog: null,
    openDetail(log) {
        this.selectedLog = log;
        this.showDetail = true;
    }
}">
    <div class="max-w-7xl mx-auto">
        
        {{-- ... (header) ... --}}

        {{-- TABLE LOGS --}}
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate__animated animate__fadeInUp">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    {{-- ... (thead) ... --}}
                    <tbody class="divide-y divide-gray-50 font-medium">
                        @forelse($logs as $log)
                        <tr @click="openDetail({{ json_encode($log) }})" class="hover:bg-indigo-50/30 transition-colors group cursor-pointer">
                            {{-- ... (tr content) ... --}}
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

    {{-- JSON Detail Modal --}}
    <div x-show="showDetail" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="showDetail = false" class="bg-gray-900 rounded-[2.5rem] shadow-2xl w-full max-w-2xl relative overflow-hidden border border-white/10">
            <div class="p-8 border-b border-white/5 bg-white/5 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter">Event <span class="text-indigo-400">Intelligence</span></h3>
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mt-1">Raw Payload Analysis</p>
                </div>
                <button @click="showDetail = false" class="text-gray-500 hover:text-white transition-colors">✕</button>
            </div>
            
            <div class="p-8 max-h-[60vh] overflow-y-auto no-scrollbar space-y-8">
                <div x-show="selectedLog?.old_values" class="space-y-3">
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest ml-1">Previous State (Old)</p>
                    <pre class="p-6 bg-black/40 rounded-2xl border border-rose-500/20 text-rose-300 font-mono text-[10px] overflow-x-auto" x-text="JSON.stringify(JSON.parse(selectedLog?.old_values || '{}'), null, 2)"></pre>
                </div>
                
                <div x-show="selectedLog?.new_values" class="space-y-3">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-1">Committed State (New)</p>
                    <pre class="p-6 bg-black/40 rounded-2xl border border-emerald-500/20 text-emerald-300 font-mono text-[10px] overflow-x-auto" x-text="JSON.stringify(JSON.parse(selectedLog?.new_values || '{}'), null, 2)"></pre>
                </div>
            </div>
            
            <div class="p-8 bg-black/20 border-t border-white/5 text-center">
                <button @click="showDetail = false" class="text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-indigo-400 transition-colors">Terminate View</button>
            </div>
        </div>
    </div>
</div>
