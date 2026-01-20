<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-8 animate__animated animate__fadeInDown">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Laporan Jaga (Aplusan)
                    </h1>
                    <p class="text-gray-500 mt-1">Monitoring situasi blok & serah terima regu jaga.</p>
                </div>
                <div class="px-4 py-2 bg-white rounded-xl shadow-md border border-gray-100/80 flex items-center gap-3">
                    <div class="h-3 w-3 rounded-full {{ str_contains($shift_name, 'Malam') ? 'bg-indigo-500' : (str_contains($shift_name, 'Pagi') ? 'bg-amber-500' : 'bg-sky-500') }}"></div>
                    <span class="font-bold text-gray-700 text-sm">{{ $shift_name }}</span>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: FORM INPUT --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <form wire:submit="submitLog" class="bg-white rounded-2xl shadow-lg border border-gray-100/80 p-6 space-y-5 animate__animated animate__fadeInUp">
                        <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">Tulis Laporan Jaga</h3>
                                <p class="text-xs text-gray-500">Isi data dengan lengkap & akurat.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Shift Jaga</label>
                            <select wire:model="shift_name" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                                <option>Regu Pagi</option>
                                <option>Regu Siang</option>
                                <option>Regu Malam</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Jumlah WBP (Warga Binaan)</label>
                            <input type="number" wire:model="wbp_count" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="0">
                            @error('wbp_count') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Situasi & Kondisi</label>
                            <textarea wire:model="description" rows="5" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none" placeholder="Laporkan kondisi terkini..."></textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <label for="is_urgent" class="flex items-center justify-between bg-gray-100 p-3 rounded-lg cursor-pointer hover:bg-red-50 border border-transparent hover:border-red-200 transition">
                            <span class="font-bold text-gray-700 text-sm">Tandai sebagai Laporan Darurat</span>
                            <input type="checkbox" wire:model="is_urgent" id="is_urgent" class="w-5 h-5 rounded-md text-red-600 focus:ring-red-500 border-gray-300">
                        </label>

                        <button type="submit" class="w-full px-6 py-3 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 flex items-center justify-center min-w-[120px]">
                            <span wire:loading.remove wire:target="submitLog">Kirim Laporan</span>
                            <div wire:loading wire:target="submitLog" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Mengirim...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: TIMELINE --}}
            <div class="lg:col-span-2 space-y-6 animate__animated animate__fadeInUp animate__delay-1s">
                @forelse($logs as $log)
                <div wire:key="{{ $log->id }}" class="group bg-white rounded-2xl p-5 shadow-md border border-gray-100/80 hover:border-indigo-200/50 transition-all duration-300 relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-2xl {{ $log->is_urgent ? 'bg-red-500' : 'bg-gradient-to-b from-indigo-500 to-purple-500' }}"></div>
                    @if(auth()->user()->role === 'admin' || auth()->id() === $log->user_id)
                    <button wire:click="deleteLog({{ $log->id }})" class="absolute top-4 right-4 h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-100 hover:text-red-600 transition-all opacity-0 group-hover:opacity-100 z-10" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    @endif
                    <div class="flex gap-4 ml-2">
                        <div class="shrink-0 h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-lg border-2 border-white shadow-sm">
                            {{ strtoupper(substr($log->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                                <h4 class="font-bold text-gray-800 text-lg">{{ $log->user->name }}</h4>
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold border {{ 
                                    str_contains($log->shift_name, 'Malam') ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 
                                    (str_contains($log->shift_name, 'Pagi') ? 'bg-amber-50 text-amber-700 border-amber-200' : 
                                    'bg-sky-50 text-sky-700 border-sky-200') 
                                }}">{{ $log->shift_name }}</span>
                                @if($log->is_urgent)
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-red-100 text-red-700 border border-red-200 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    DARURAT
                                </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 font-medium mb-3">{{ $log->created_at->isoFormat('dddd, D MMMM YYYY - HH:mm') }}</p>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-sm bg-gray-50 rounded-lg p-4 border border-gray-200/50">{{ $log->description }}</p>
                            <div class="mt-3 text-sm font-bold text-indigo-600">Jumlah WBP: {{ $log->wbp_count }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5l.415-.207a.75.75 0 011.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v-1.5m0 1.5v-1.5m-6.338 2.553a9 9 0 1012.676 0M6.343 12a9 9 0 1111.314 0" /></svg>
                    <h3 class="text-xl font-semibold text-gray-700">Belum Ada Laporan</h3>
                    <p class="mt-1 text-gray-500">Jadilah yang pertama mengisi laporan jaga hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>