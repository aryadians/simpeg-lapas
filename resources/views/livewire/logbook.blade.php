<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-8 animate__animated animate__fadeInDown">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Laporan Jaga (Aplusan)
                    </h1>
                    <p class="text-gray-600 mt-1">Monitoring situasi blok & serah terima regu jaga.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-white rounded-xl shadow-lg border border-gray-100/80 flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full {{ str_contains($shift_name, 'Malam') ? 'bg-indigo-500' : (str_contains($shift_name, 'Pagi') ? 'bg-amber-500' : 'bg-sky-500') }}"></div>
                        <span class="font-bold text-gray-700 text-sm">{{ $shift_name }}</span>
                    </div>
                    
                    <button wire:click.prevent="showCreateForm" type="button" class="group relative px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/40 hover:-translate-y-1 flex items-center gap-2">
                        <span class="absolute inset-0 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-xl opacity-0 transition-opacity duration-300 group-hover:opacity-50 blur-md"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4z" /></svg>
                        <span class="relative">Buat Laporan Baru</span>
                    </button>
                </div>
            </div>
        </header>

        {{-- FORM MODAL --}}
        @if($showForm)
        <div 
            x-data="{ show: @entangle('showForm') }"
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            {{-- Backdrop --}}
            <div wire:click="cancel" class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>

            {{-- Modal Panel --}}
            <form wire:submit.prevent="submitLog"
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90 [transform:rotateX(-20deg)]"
                x-transition:enter-end="opacity-100 transform scale-100 [transform:rotateX(0deg)]"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100 [transform:rotateX(0deg)]"
                x-transition:leave-end="opacity-0 transform scale-90 [transform:rotateX(-20deg)]"
                class="relative w-full max-w-lg bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-indigo-500/20 border border-white/20 [transform-style:preserve-3d]"
            >
                <div class="p-8">
                    {{-- Modal Header --}}
                    <div class="flex items-start gap-4 mb-6">
                        <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 flex-none flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <div>
                            <h3 id="modal-title" class="font-extrabold text-gray-800 text-2xl">Buat Laporan Jaga Baru</h3>
                            <p class="text-sm text-gray-500 mt-1">Isi semua field untuk membuat laporan jaga baru.</p>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Shift Jaga --}}
                            <div>
                                <label for="shift_name" class="block text-sm font-bold text-gray-700 mb-2">Shift Jaga</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <select id="shift_name" wire:model="shift_name" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300">
                                        <option>Regu Pagi</option>
                                        <option>Regu Siang</option>
                                        <option>Regu Malam</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Jumlah WBP --}}
                            <div>
                                <label for="wbp_count" class="block text-sm font-bold text-gray-700 mb-2">Jumlah WBP</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.175A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.098-.611.106-.925a1 1 0 00-1-1h-2.5a1 1 0 00-1 1 4.5 4.5 0 01.106 4.5H18a1 1 0 001-1v-2.5a1 1 0 00-1-1h-3.5z" /></svg>
                                    </div>
                                    <input id="wbp_count" type="number" wire:model.lazy="wbp_count" placeholder="0" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300">
                                </div>
                                @error('wbp_count') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        {{-- Situasi & Kondisi --}}
                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Situasi & Kondisi</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 pt-3">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg>
                                </div>
                                <textarea id="description" wire:model.lazy="description" rows="5" placeholder="Laporkan situasi, kondisi, dan kejadian menonjol..." class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300 resize-none"></textarea>
                            </div>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Laporan Darurat --}}
                        <div>
                            <label for="is_urgent" class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all duration-300"
                                :class="{ 'bg-red-100/80 border-red-300/50 shadow-lg shadow-red-500/10': $wire.is_urgent, 'bg-gray-50/60 hover:bg-red-50/50 border border-transparent hover:border-red-300/50': !$wire.is_urgent }"
                            >
                                <input type="checkbox" wire:model="is_urgent" id="is_urgent" class="h-6 w-6 rounded-md border-gray-300 text-red-600 shadow-sm focus:ring-red-500 transition-transform duration-300" :class="{ 'scale-110': $wire.is_urgent }">
                                <div class="flex-grow">
                                    <span class="font-bold text-sm transition-colors" :class="{ 'text-red-800': $wire.is_urgent, 'text-gray-700': !$wire.is_urgent }">Tandai sebagai Laporan Darurat</span>
                                    <p class="text-xs text-gray-600">Pilih ini jika laporan bersifat genting atau butuh perhatian segera.</p>
                                </div>
                                <div x-show="$wire.is_urgent" x-transition class="text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer / Actions --}}
                <div class="flex items-center justify-end gap-4 bg-gray-50/30 px-8 py-5 mt-6 rounded-b-2xl border-t border-white/20">
                    <button wire:click.prevent="cancel" type="button" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-transparent rounded-lg hover:bg-red-100/50 hover:text-red-700 transition-all duration-300">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 active:scale-95">
                        <span wire:loading.remove wire:target="submitLog">Kirim Laporan</span>
                        <div wire:loading wire:target="submitLog" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Mengirim...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- TIMELINE --}}
        <div class="space-y-12 animate__animated animate__fadeInUp animate__delay-1s">
            @forelse($logs as $log)
            <div wire:key="{{ $log->id }}" class="group relative flex items-start gap-x-4 sm:gap-x-6">
                <!-- Timeline Connector -->
                <div class="absolute left-6 sm:left-8 top-8 w-px h-full bg-gray-200 group-last:hidden"></div>

                <!-- Avatar & Icon -->
                <div class="shrink-0 flex flex-col items-center">
                    <div class="relative z-10 h-12 w-12 sm:h-16 sm:w-16 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-lg border-4 border-gray-50 shadow-md">
                        {{ strtoupper(substr($log->user->name, 0, 2)) }}
                    </div>
                </div>

                <!-- Log Card -->
                <div class="flex-1 mt-1.5 [perspective:1000px]">
                    <div class="group/card bg-white rounded-2xl p-5 shadow-lg border border-gray-200/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20 [transform:rotateX(5deg)] hover:[transform:rotateX(0deg)]">
                        <div class="absolute -inset-px rounded-2xl bg-gradient-to-br from-sky-400 to-indigo-600 opacity-0 group-hover/card:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative">
                            @if($log->is_urgent)
                                <div class="absolute -top-8 -right-8 h-16 w-16 text-red-500 opacity-10 group-hover/card:opacity-20 transition-opacity duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </div>
                            @endif

                            @if(auth()->user()->role === 'admin' || auth()->id() === $log->user_id)
                            <button wire:click="deleteLog({{ $log->id }})" class="absolute top-2 right-2 h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-100 hover:text-red-600 transition-all opacity-0 group-hover/card:opacity-100 z-10" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                            @endif

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
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-sm bg-gray-50/70 rounded-lg p-4 border border-gray-200/50">{{ $log->description }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100 text-sm font-bold text-indigo-600">Jumlah WBP: {{ $log->wbp_count }}</div>
                        </div>
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