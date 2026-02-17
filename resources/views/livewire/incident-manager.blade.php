<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-rose-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center lg:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Incident <span class="text-rose-600">Response</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Registry Kejadian & Tindakan Darurat</p>
                </div>

                <button wire:click="create" class="px-8 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-black rounded-2xl shadow-lg shadow-rose-500/30 transform transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest text-xs relative z-10">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" stroke-width="1"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>New Report</span>
                </button>
            </div>
        </header>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 animate__animated animate__fadeInUp">
            @forelse ($reports as $report)
                <div wire:key="{{ $report->id }}" class="group bg-white rounded-[2rem] shadow-lg border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:border-rose-200 relative overflow-hidden flex flex-col">
                    <div class="p-8 flex-1">
                        <div class="flex justify-between items-start mb-6">
                            <div class="space-y-1">
                                <p class="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em]">{{ $report->post->name ?? 'GENERAL' }}</p>
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight group-hover:text-rose-600 transition-colors">{{ $report->title }}</h3>
                            </div>
                            <span class="px-3 py-1 text-[9px] font-black rounded-full uppercase tracking-widest border shadow-sm
                                @if($report->status == 'Baru') bg-sky-50 text-sky-700 border-sky-100 @endif
                                @if($report->status == 'Ditinjau') bg-amber-50 text-amber-700 border-amber-100 @endif
                                @if($report->status == 'Selesai') bg-emerald-50 text-emerald-700 border-emerald-100 @endif
                            ">
                                {{ $report->status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed line-clamp-3 bg-gray-50 p-4 rounded-2xl border border-gray-100/50 mb-6 group-hover:bg-rose-50 transition-colors">{{ $report->description }}</p>
                        
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center font-black text-[10px] uppercase shadow-inner">
                                {{ strtoupper(substr($report->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-800 uppercase tracking-widest">{{ $report->user->name }}</p>
                                <p class="text-[9px] font-bold text-gray-400 font-mono">{{ $report->report_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-50 flex justify-end">
                        <button wire:click="edit({{ $report->id }})" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-rose-600 transition-colors flex items-center gap-2">
                            Review Details
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100">
                    <div class="flex flex-col items-center justify-center opacity-30 grayscale">
                        <span class="text-7xl mb-6">📢</span>
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">No Incidents Logged</h3>
                        <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em]">Operational perimeter is currently secure.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $reports->links() }}
        </div>
    </div>

    {{-- MODAL FORM --}}
    @if ($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div wire:click="cancel" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>

            {{-- Modal Panel --}}
            <div class="relative w-full max-w-3xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster border border-white/20">
                @if ($isReadOnly)
                    {{-- READ ONLY / DETAIL VIEW --}}
                    <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-2xl bg-rose-600 flex items-center justify-center text-white shadow-xl shadow-rose-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">{{ $title }}</h3>
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1">Registry Record #{{ $reportId }}</p>
                            </div>
                        </div>
                        <button wire:click="cancel" class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-400 flex items-center justify-center hover:bg-gray-100 transition-all">✕</button>
                    </div>
                    
                    <div class="p-8 max-h-[60vh] overflow-y-auto no-scrollbar">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Time Identity</p>
                                <p class="text-sm font-black text-gray-800 uppercase">{{ \Carbon\Carbon::parse($report_date)->format('d M Y') }} • {{ $report_time }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sector / Post</p>
                                <p class="text-sm font-black text-gray-800 uppercase">{{ $post_id ? (\App\Models\Post::find($post_id)->name ?? 'GENERAL') : 'GENERAL' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Current Status</p>
                                <p class="text-sm font-black text-gray-800 uppercase">{{ $status }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Involved Entities</label>
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 font-bold text-sm text-gray-700">{{ $people_involved ?: 'NO ENTITIES SPECIFIED' }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Event Narrative</label>
                                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 font-medium text-gray-700 leading-relaxed text-sm whitespace-pre-wrap">{{ $description }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex justify-end gap-3">
                        <button wire:click="switchToEditMode" class="px-8 py-3.5 bg-rose-600 text-white font-black rounded-2xl shadow-lg shadow-rose-500/20 hover:bg-rose-700 transition-all active:scale-95 uppercase tracking-widest text-[10px]">Modify Report</button>
                    </div>
                @else
                    {{-- EDIT / CREATE FORM --}}
                    <form wire:submit.prevent="save" class="flex flex-col h-full">
                        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center">{{ $reportId ? 'Modify Incident' : 'Register Incident' }}</h3>
                        </div>
                        
                        <div class="p-8 space-y-6 flex-1 overflow-y-auto no-scrollbar">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Report Heading</label>
                                <input type="text" wire:model.lazy="title" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-black uppercase tracking-tight" placeholder="Subject Title">
                                @error('title') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Date Registry</label>
                                    <input type="date" wire:model.lazy="report_date" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-bold">
                                    @error('report_date') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Time Registry</label>
                                    <input type="time" wire:model.lazy="report_time" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-bold">
                                    @error('report_time') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sector Authority</label>
                                    <select wire:model.lazy="post_id" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-bold">
                                        <option value="">GENERAL / NO POST</option>
                                        @foreach($allPosts as $post)
                                            <option value="{{ $post->id }}">{{ $post->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($reportId)
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Status Protocol</label>
                                    <select wire:model.lazy="status" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-bold uppercase tracking-widest text-xs">
                                        <option>Baru</option>
                                        <option>Ditinjau</option>
                                        <option>Selesai</option>
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Entities Involved</label>
                                <input type="text" wire:model.lazy="people_involved" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-bold" placeholder="Names of persons / IDs">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Narrative Description</label>
                                <textarea wire:model.lazy="description" rows="5" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-[2rem] p-6 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition resize-none font-medium" placeholder="Describe the incident in detail..."></textarea>
                                @error('description') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center gap-3">
                            <button wire:click.prevent="cancel" type="button" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Dismiss</button>
                            <button type="submit" class="flex-[2] py-4 text-white bg-rose-600 hover:bg-rose-700 rounded-2xl font-black shadow-lg shadow-rose-500/30 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">
                                <span wire:loading.remove wire:target="save">Commit Report</span>
                                <div wire:loading wire:target="save">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
