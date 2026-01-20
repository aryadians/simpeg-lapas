<div class="min-h-screen bg-slate-100 p-6 md:p-10 font-sans relative overflow-hidden">

    {{-- Background Decoration --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 translate-y-1/2 animate-blob animation-delay-2000"></div>

    {{-- HEADER --}}
    <div class="max-w-6xl mx-auto mb-10 relative z-10">
        <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 drop-shadow-sm">
            E-Cuti & Perizinan
        </h1>
        <p class="text-slate-500 mt-2 text-lg font-medium">Ajukan permohonan libur dengan mudah & cepat.</p>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
        
        {{-- KOLOM KIRI: FORM PENGAJUAN (Sticky) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl shadow-purple-500/10 border border-white/50 p-6 relative overflow-hidden">
                    
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Form Cuti</h3>
                            <p class="text-xs text-slate-500">Pastikan tanggal sesuai</p>
                        </div>
                    </div>
                    
                    <form wire:submit="submitRequest" class="space-y-5">
                        
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Mulai --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Dari</label>
                                <input wire:model="start_date" type="date" class="w-full bg-slate-50 border-0 rounded-xl p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-purple-500 transition-all shadow-inner">
                                @error('start_date') <span class="text-rose-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                            {{-- Sampai --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Sampai</label>
                                <input wire:model="end_date" type="date" class="w-full bg-slate-50 border-0 rounded-xl p-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-purple-500 transition-all shadow-inner">
                                @error('end_date') <span class="text-rose-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Alasan --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Alasan</label>
                            <textarea wire:model="reason" rows="4" 
                                      class="w-full bg-slate-50 border-0 rounded-xl p-4 text-slate-700 focus:ring-2 focus:ring-purple-500 transition-all shadow-inner resize-none placeholder-slate-400" 
                                      placeholder="Contoh: Menghadiri pernikahan saudara kandung di luar kota..."></textarea>
                            @error('reason') <span class="text-rose-500 text-xs font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-purple-600/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            <span>Ajukan Sekarang</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STATUS & APPROVAL --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- ADMIN APPROVAL CARD --}}
            @if($isAdmin && count($pendingRequests) > 0)
            <div class="bg-amber-50 rounded-3xl p-6 border border-amber-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-200 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -translate-y-1/2 translate-x-1/2"></div>
                
                <h3 class="text-lg font-black text-amber-800 mb-4 flex items-center gap-2 relative z-10">
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                    Menunggu Persetujuan ({{ count($pendingRequests) }})
                </h3>

                <div class="grid gap-4 relative z-10">
                    @foreach($pendingRequests as $req)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-amber-100/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 flex items-center justify-center font-bold text-slate-600">
                                {{ substr($req->user->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $req->user->name }}</h4>
                                <p class="text-xs text-slate-500 font-medium">
                                    {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                </p>
                                <p class="text-sm text-slate-600 mt-1 italic">"{{ $req->reason }}"</p>
                            </div>
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button wire:click="approve({{ $req->id }})" class="flex-1 sm:flex-none px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-xl hover:bg-emerald-200 transition text-sm">
                                Terima
                            </button>
                            <button wire:click="reject({{ $req->id }})" class="flex-1 sm:flex-none px-4 py-2 bg-rose-100 text-rose-700 font-bold rounded-xl hover:bg-rose-200 transition text-sm">
                                Tolak
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- HISTORY LIST --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-800 text-lg mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Riwayat Pengajuan Saya
                </h3>

                <div class="space-y-4">
                    @forelse($myRequests as $myReq)
                    <div class="group flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center text-lg shadow-sm
                                {{ $myReq->status == 'approved' ? 'bg-emerald-100 text-emerald-600' : 
                                  ($myReq->status == 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600') }}">
                                {{ $myReq->status == 'approved' ? '✔' : ($myReq->status == 'rejected' ? '✖' : '⏳') }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-700 text-sm group-hover:text-purple-600 transition-colors">{{ $myReq->reason }}</p>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    📅 {{ \Carbon\Carbon::parse($myReq->start_date)->format('d M') }} s/d {{ \Carbon\Carbon::parse($myReq->end_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                            {{ $myReq->status == 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 
                              ($myReq->status == 'rejected' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                            {{ $myReq->status == 'pending' ? 'Menunggu' : ($myReq->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <div class="inline-block p-4 rounded-full bg-slate-50 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        </div>
                        <p class="text-slate-400 font-medium text-sm">Belum ada riwayat pengajuan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>