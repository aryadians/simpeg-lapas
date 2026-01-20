<div class="min-h-screen bg-gray-100 p-8 font-sans">
    
    <div class="max-w-7xl mx-auto mb-8">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
            E-Cuti & Izin
        </h1>
        <p class="text-gray-500">Ajukan permohonan libur agar tidak terkena jadwal dinas.</p>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- KOLOM KIRI: FORM PENGAJUAN --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 h-fit">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                📝 Form Pengajuan
            </h3>
            
            <form wire:submit="submitRequest" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mulai Tanggal</label>
                        <input wire:model="start_date" type="date" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input wire:model="end_date" type="date" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500">
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Alasan / Keperluan</label>
                    <textarea wire:model="reason" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500" placeholder="Contoh: Sakit demam / Acara nikahan keluarga"></textarea>
                    @error('reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-lg text-sm px-5 py-3 text-center shadow-lg transition transform hover:-translate-y-1">
                    Kirim Permohonan
                </button>
            </form>
        </div>

        {{-- KOLOM KANAN: LIST & APPROVAL --}}
        <div class="space-y-6">
            
            {{-- BAGIAN ADMIN: APPROVAL LIST (Hanya muncul jika Admin) --}}
            @if($isAdmin && count($pendingRequests) > 0)
            <div class="bg-yellow-50 rounded-2xl shadow-lg p-6 border border-yellow-200">
                <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center gap-2">
                    ⚡ Butuh Persetujuan ({{ count($pendingRequests) }})
                </h3>
                <div class="space-y-3">
                    @foreach($pendingRequests as $req)
                    <div class="bg-white p-4 rounded-xl border border-yellow-100 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-800">{{ $req->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $req->reason }}</p>
                                <p class="text-xs font-mono text-indigo-600 mt-1">
                                    {{ \Carbon\Carbon::parse($req->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="approve({{ $req->id }})" class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200">✔</button>
                                <button wire:click="reject({{ $req->id }})" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">✖</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- RIWAYAT SAYA --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📂 Riwayat Pengajuan Saya</h3>
                <div class="overflow-y-auto max-h-96 space-y-3">
                    @forelse($myRequests as $myReq)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div>
                            <p class="text-sm font-bold text-gray-700">{{ $myReq->reason }}</p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($myReq->start_date)->format('d M') }} s/d {{ \Carbon\Carbon::parse($myReq->end_date)->format('d M Y') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded 
                            {{ $myReq->status == 'approved' ? 'bg-green-100 text-green-700' : 
                              ($myReq->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($myReq->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 text-sm py-4">Belum ada riwayat cuti.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>