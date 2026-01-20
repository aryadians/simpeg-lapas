<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-8 animate__animated animate__fadeInDown">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                E-Cuti & Perizinan
            </h1>
            <p class="text-gray-500 mt-1">Ajukan permohonan, pantau status, dan kelola persetujuan dengan mudah.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: FORM PENGAJUAN (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-6 animate__animated animate__fadeInUp">
                        <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">Form Pengajuan Cuti</h3>
                                <p class="text-xs text-gray-500">Pastikan data yang diisi sudah benar.</p>
                            </div>
                        </div>
                        
                        <form wire:submit="submitRequest" class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Mulai Tanggal</label>
                                    <input wire:model="start_date" type="date" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Sampai Tanggal</label>
                                    <input wire:model="end_date" type="date" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Alasan</label>
                                <textarea wire:model="reason" rows="4" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none" placeholder="Cth: Cuti tahunan"></textarea>
                                @error('reason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="w-full px-6 py-3 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 flex items-center justify-center min-w-[120px]">
                                <span wire:loading.remove wire:target="submitRequest">Ajukan Sekarang</span>
                                <div wire:loading wire:target="submitRequest" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Mengirim...</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: STATUS & APPROVAL --}}
            <div class="lg:col-span-2 space-y-8">
                @if($isAdmin && count($pendingRequests) > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-6 animate__animated animate__fadeInUp">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-3">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        Menunggu Persetujuan ({{ count($pendingRequests) }})
                    </h3>
                    <div class="space-y-4">
                        @foreach($pendingRequests as $req)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-lg shrink-0">
                                    {{ strtoupper(substr($req->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $req->user->name }}</h4>
                                    <p class="text-xs text-gray-500 font-medium">
                                        {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1 italic">"{{ $req->reason }}"</p>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto self-end sm:self-center">
                                <button wire:click="reject({{ $req->id }})" class="flex-1 sm:flex-none px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200 transition text-sm">Tolak</button>
                                <button wire:click="approve({{ $req->id }})" class="flex-1 sm:flex-none px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-lg hover:bg-emerald-200 transition text-sm">Setujui</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-6 animate__animated animate__fadeInUp animate__delay-1s">
                    <h3 class="font-bold text-gray-800 text-lg mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat Pengajuan Saya
                    </h3>
                    <div class="space-y-4">
                        @forelse($myRequests as $myReq)
                        <div class="group flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-indigo-50/50 hover:border-indigo-100 transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center text-lg {{ 
                                    ['approved' => 'bg-emerald-100 text-emerald-600',
                                     'rejected' => 'bg-red-100 text-red-600',
                                     'pending' => 'bg-amber-100 text-amber-600'][$myReq->status] 
                                }}">
                                    @if($myReq->status == 'approved')<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    @elseif($myReq->status == 'rejected')<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    @else<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-spin" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-700 text-sm group-hover:text-indigo-600 transition-colors">{{ $myReq->reason }}</p>
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">📅 {{ \Carbon\Carbon::parse($myReq->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($myReq->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wide {{ 
                                ['approved' => 'bg-emerald-100 text-emerald-700',
                                 'rejected' => 'bg-red-100 text-red-700',
                                 'pending' => 'bg-amber-100 text-amber-700'][$myReq->status] 
                            }}">
                                {{ $myReq->status }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 font-medium text-sm">Belum ada riwayat pengajuan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>