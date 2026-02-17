<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">E-Leave <span class="text-indigo-600">Portal</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Sistem Pengajuan Cuti Digital</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: FORM PENGAJUAN (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 animate__animated animate__fadeInUp overflow-hidden relative">
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-40"></div>
                        
                        <div class="flex items-center gap-4 mb-8 relative z-10">
                            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 uppercase tracking-tight">New Request</h3>
                                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Absence Permission</p>
                            </div>
                        </div>
                        
                        <form wire:submit="submitRequest" class="space-y-6 relative z-10">
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Start Date</label>
                                    <input wire:model="start_date" type="date" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                                    @error('start_date') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">End Date</label>
                                    <input wire:model="end_date" type="date" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                                    @error('end_date') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reason / Statement</label>
                                <textarea wire:model="reason" rows="4" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none font-medium text-sm" placeholder="Purpose of leave..."></textarea>
                                @error('reason') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full py-4 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-lg shadow-indigo-500/20 transform transition-all active:scale-95 flex items-center justify-center uppercase tracking-[0.2em] text-xs">
                                <span wire:loading.remove wire:target="submitRequest">Confirm Request</span>
                                <div wire:loading wire:target="submitRequest">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: STATUS & APPROVAL --}}
            <div class="lg:col-span-2 space-y-8">
                @if($isAdmin && count($pendingRequests) > 0)
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 animate__animated animate__fadeInUp">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter mb-6 flex items-center gap-3">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        Pending Authorization ({{ count($pendingRequests) }})
                    </h3>
                    <div class="space-y-4">
                        @foreach($pendingRequests as $req)
                        <div class="bg-gray-50/50 p-6 rounded-[1.5rem] border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 group hover:bg-white hover:shadow-md transition-all duration-300">
                            <div class="flex items-center gap-5">
                                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-black text-gray-500 text-xl shadow-inner shrink-0 group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white transition-all duration-500">
                                    {{ strtoupper(substr($req->user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-gray-900 uppercase tracking-tight truncate">{{ $req->user->name }}</h4>
                                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-1">
                                        {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} — {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-2 font-medium bg-white p-3 rounded-xl border border-gray-50 shadow-sm">"{{ $req->reason }}"</p>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto shrink-0">
                                <button wire:click="reject({{ $req->id }})" class="flex-1 sm:flex-none px-6 py-3 bg-white text-rose-600 font-black rounded-xl border border-rose-100 hover:bg-rose-50 transition-all uppercase tracking-widest text-[10px] active:scale-95 shadow-sm">Reject</button>
                                <button wire:click="approve({{ $req->id }})" class="flex-1 sm:flex-none px-6 py-3 bg-indigo-600 text-white font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 transition-all uppercase tracking-widest text-[10px] active:scale-95">Approve</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 animate__animated animate__fadeInUp">
                    <h3 class="font-black text-gray-900 text-lg uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Request Logs
                    </h3>
                    <div class="space-y-4">
                        @forelse($myRequests as $myReq)
                        <div class="group flex items-center justify-between p-6 bg-gray-50/50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-lg hover:border-indigo-100 transition-all duration-500 relative overflow-hidden">
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="h-12 w-12 rounded-xl flex items-center justify-center shadow-sm border {{ 
                                    ['approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                     'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                     'pending' => 'bg-amber-50 text-amber-600 border-amber-100'][$myReq->status] 
                                }}">
                                    @if($myReq->status == 'approved')<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    @elseif($myReq->status == 'rejected')<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    @else<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-pulse" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-gray-800 text-sm uppercase tracking-tight group-hover:text-indigo-600 transition-colors truncate">{{ $myReq->reason }}</p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Period: {{ \Carbon\Carbon::parse($myReq->start_date)->format('d M') }} — {{ \Carbon\Carbon::parse($myReq->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="px-4 py-1.5 text-[9px] font-black rounded-full uppercase tracking-[0.2em] relative z-10 border shadow-sm {{ 
                                ['approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                 'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                                 'pending' => 'bg-amber-50 text-amber-700 border-amber-100'][$myReq->status] 
                            }}">
                                {{ $myReq->status }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                            <span class="text-5xl block mb-4 opacity-20 grayscale">📂</span>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Vault Empty</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
