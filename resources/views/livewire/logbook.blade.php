<div class="min-h-screen bg-slate-100 p-6 md:p-10 font-sans relative overflow-hidden">

    {{-- Background Decoration (Blur Blobs) --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 -translate-y-1/2 animate-blob animation-delay-2000"></div>

    {{-- HEADER --}}
    <div class="max-w-6xl mx-auto mb-10 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 drop-shadow-sm">
                    Laporan Aplusan
                </h1>
                <p class="text-slate-500 mt-2 text-lg font-medium">Monitoring situasi blok & serah terima regu jaga.</p>
            </div>
            
            {{-- Indikator Shift Realtime --}}
            <div class="px-5 py-2 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center gap-3">
                <div class="h-3 w-3 rounded-full {{ $shift_name == 'Regu Malam' ? 'bg-indigo-900 animate-pulse' : ($shift_name == 'Regu Pagi' ? 'bg-yellow-400 animate-pulse' : 'bg-blue-400 animate-pulse') }}"></div>
                <span class="font-bold text-slate-700">{{ $shift_name }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 relative z-10">
        
        {{-- KOLOM KIRI: FORM INPUT (Sticky & Glassmorphism) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-500/10 border border-white/50 p-6 relative overflow-hidden group hover:shadow-indigo-500/20 transition-all duration-500">
                    
                    {{-- Header Form --}}
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Tulis Laporan</h3>
                            <p class="text-xs text-slate-500">Isi data dengan lengkap</p>
                        </div>
                    </div>
                    
                    <form wire:submit="submitLog" class="space-y-5">
                        
                        {{-- Input Shift (DROPDOWN) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Shift</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                </div>
                                <select wire:model="shift_name" 
                                        class="w-full bg-slate-50 text-slate-800 border-0 rounded-xl pl-10 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all shadow-inner font-bold cursor-pointer appearance-none">
                                    <option value="Regu Pagi">Regu Pagi (07.00 - 13.00)</option>
                                    <option value="Regu Siang">Regu Siang (13.00 - 19.00)</option>
                                    <option value="Regu Malam">Regu Malam (19.00 - 07.00)</option>
                                </select>
                                {{-- Panah Dropdown Custom --}}
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Input WBP --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Jumlah WBP</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
                                </div>
                                <input type="number" wire:model="wbp_count" 
                                       class="w-full bg-slate-50 text-slate-800 border-0 rounded-xl pl-10 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all shadow-inner font-bold placeholder-slate-300" 
                                       placeholder="0">
                            </div>
                            @error('wbp_count') <span class="text-rose-500 text-xs font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Situasi & Kondisi</label>
                            <div class="relative group/input">
                                <textarea wire:model="description" rows="5" 
                                          class="w-full bg-slate-50 text-slate-800 border-0 rounded-xl p-4 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all shadow-inner placeholder-slate-300 resize-none leading-relaxed" 
                                          placeholder="Laporkan kondisi keamanan blok, sarana prasarana, atau kejadian menonjol..."></textarea>
                            </div>
                            @error('description') <span class="text-rose-500 text-xs font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Toggle Urgent --}}
                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-100 cursor-pointer" onclick="document.getElementById('urgent').click()">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700">Tandai Darurat?</span>
                            </div>
                            <input type="checkbox" wire:model="is_urgent" id="urgent" class="w-5 h-5 rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                            <span>Kirim Laporan</span>
                            <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TIMELINE (Scrollable) --}}
        <div class="lg:col-span-2 space-y-6">
            
            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 bg-white/50 rounded-3xl border border-dashed border-slate-300">
                    <div class="h-20 w-20 bg-slate-100 rounded-full flex items-center justify-center text-4xl mb-4">📭</div>
                    <p class="text-slate-400 font-medium">Belum ada laporan hari ini.</p>
                </div>
            @endif

            @foreach($logs as $log)
            <div class="group relative bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 transform hover:-translate-y-1 hover:scale-[1.01] overflow-hidden">
                
                {{-- Border Kiri (Indikator Warna) --}}
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $log->is_urgent ? 'bg-rose-500' : 'bg-gradient-to-b from-indigo-500 to-purple-500' }}"></div>

                {{-- Tombol Hapus (Hover Only) --}}
                @if(auth()->user()->role === 'admin' || auth()->id() === $log->user_id)
                <button wire:click="deleteLog({{ $log->id }})" 
                        wire:confirm="Yakin ingin menghapus laporan ini?"
                        class="absolute top-4 right-4 h-8 w-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:bg-rose-100 hover:text-rose-600 transition-all opacity-0 group-hover:opacity-100 z-10" title="Hapus Laporan">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
                @endif
                
                <div class="flex gap-5">
                    {{-- Avatar --}}
                    <div class="shrink-0">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-slate-100 to-slate-200 flex items-center justify-center font-bold text-slate-600 text-sm shadow-inner border border-white">
                            {{ substr($log->user->name, 0, 2) }}
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h4 class="font-bold text-slate-800 text-lg">{{ $log->user->name }}</h4>
                            
                            {{-- Badge Shift --}}
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-bold border 
                                {{ $log->shift_name == 'Regu Malam' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                                  ($log->shift_name == 'Regu Pagi' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : 'bg-blue-50 text-blue-700 border-blue-100') }}">
                                {{ $log->shift_name }}
                            </span>

                            {{-- Badge Urgent --}}
                            @if($log->is_urgent)
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100 flex items-center gap-1 animate-pulse">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    URGENT
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-xs text-slate-400 font-medium mb-3 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $log->created_at->diffForHumans() }}
                        </p>

                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100/50 text-slate-600 leading-relaxed whitespace-pre-line text-sm shadow-sm">
                            {{ $log->description }}
                        </div>

                        <div class="mt-4 flex items-center gap-6">
                            <div class="flex items-center gap-2 text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
                                <span class="text-xs font-bold">Total WBP: {{ $log->wbp_count }} Orang</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>