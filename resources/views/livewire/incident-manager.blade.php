<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <header class="mb-8 animate__animated animate__fadeInDown">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        Laporan Kejadian
                    </h1>
                    <p class="text-gray-600 mt-1">Pantau dan kelola semua laporan kejadian internal di sini.</p>
                </div>
                <button wire:click="create" type="button" class="group relative px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/40 hover:-translate-y-1 flex items-center gap-2">
                    <span class="absolute inset-0 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-xl opacity-0 transition-opacity duration-300 group-hover:opacity-50 blur-md"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4z" /></svg>
                    <span class="relative">Buat Laporan Kejadian</span>
                </button>
            </div>
        </header>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-6 rounded-r-lg shadow-md animate__animated animate__fadeIn" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg></div>
                    <div>
                        <p class="font-bold">Sukses</p>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($reports as $report)
                <div wire:key="{{ $report->id }}" class="group relative bg-white rounded-2xl p-5 shadow-lg border border-gray-100/50 transition-all duration-300 transform hover:-translate-y-1.5 hover:shadow-indigo-500/20">
                    <div class="absolute -inset-px rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-sm"></div>
                    <div class="relative h-full flex flex-col bg-white rounded-xl p-1">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-xl 
                            @if($report->status == 'Baru') bg-sky-500 @endif
                            @if($report->status == 'Ditinjau') bg-yellow-500 @endif
                            @if($report->status == 'Selesai') bg-green-500 @endif
                        "></div>
                        <div class="ml-2 flex-grow flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $report->post->name ?? 'Tanpa Pos Jaga' }}</p>
                                    <a wire:click="edit({{ $report->id }})" class="cursor-pointer block mt-1 text-lg leading-tight font-bold text-gray-900 group-hover:text-indigo-700 transition">{{ $report->title }}</a>
                                </div>
                                <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($report->status == 'Baru') bg-sky-100 text-sky-800 @endif
                                    @if($report->status == 'Ditinjau') bg-yellow-100 text-yellow-800 @endif
                                    @if($report->status == 'Selesai') bg-green-100 text-green-800 @endif
                                ">
                                    {{ $report->status }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed line-clamp-3 flex-grow">{{ $report->description }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="text-sm">
                                    <p class="font-semibold text-gray-800">{{ $report->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->report_date->format('d M Y') }}</p>
                                </div>
                                <button wire:click="edit({{ $report->id }})" class="px-4 py-2 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-all duration-300">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                     <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        <h3 class="text-xl font-semibold text-gray-700">Belum Ada Laporan Kejadian</h3>
                        <p class="mt-1 text-gray-500">Klik "Buat Laporan Kejadian" untuk menambahkan laporan pertama.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>

    {{-- MODAL FORM --}}
    @if ($showModal)
        <div
            x-data="{ show: @entangle('showModal') }"
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
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90 [transform:rotateX(-20deg)]"
                x-transition:enter-end="opacity-100 transform scale-100 [transform:rotateX(0deg)]"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100 [transform:rotateX(0deg)]"
                x-transition:leave-end="opacity-0 transform scale-90 [transform:rotateX(-20deg)]"
                class="relative w-full max-w-3xl bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-indigo-500/20 border border-white/20 [transform-style:preserve-3d]"
            >
                @if ($isReadOnly)
                    {{-- READ ONLY / DETAIL VIEW --}}
                    <div class="p-8">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 flex-none flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <h3 id="modal-title" class="font-extrabold text-gray-800 text-2xl">{{ $title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Detail Laporan Kejadian</p>
                            </div>
                        </div>
                        <dl class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div><dt class="text-sm font-bold text-gray-600">Tanggal & Waktu</dt><dd class="mt-1 text-gray-800 font-semibold">{{ \Carbon\Carbon::parse($report_date)->format('d M Y') }}, {{ $report_time }}</dd></div>
                                <div><dt class="text-sm font-bold text-gray-600">Pos Jaga</dt><dd class="mt-1 text-gray-800 font-semibold">{{ $post_id ? (\App\Models\Post::find($post_id)->name ?? 'N/A') : 'N/A' }}</dd></div>
                                <div><dt class="text-sm font-bold text-gray-600">Status</dt><dd class="mt-1"><span class="px-3 py-1 text-xs font-semibold rounded-full @if($status == 'Baru') bg-sky-100 text-sky-800 @endif @if($status == 'Ditinjau') bg-yellow-100 text-yellow-800 @endif @if($status == 'Selesai') bg-green-100 text-green-800 @endif">{{ $status }}</span></dd></div>
                            </div>
                            <div><dt class="text-sm font-bold text-gray-600">Pihak Terlibat</dt><dd class="mt-1 text-gray-800 font-semibold">{{ $people_involved ?? 'Tidak ada' }}</dd></div>
                            <div><dt class="text-sm font-bold text-gray-600">Uraian Kejadian</dt><dd class="mt-2 text-gray-700 whitespace-pre-wrap bg-gray-50/70 p-4 rounded-lg border border-gray-200/50">{{ $description }}</dd></div>
                        </dl>
                    </div>
                    <div class="flex items-center justify-end gap-4 bg-gray-50/30 px-8 py-5 mt-6 rounded-b-2xl border-t border-white/20">
                        <button wire:click="$set('showModal', false)" type="button" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-transparent rounded-lg hover:bg-gray-200/50 transition-all duration-300">Tutup</button>
                        <button wire:click="switchToEditMode" type="button" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 active:scale-95">Edit Laporan</button>
                    </div>
                @else
                                        {{-- EDIT / CREATE FORM --}}
                                        <form wire:submit.prevent="save">
                                            <div class="p-8">
                                                <div class="flex items-start gap-4 mb-6">
                                                    <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 flex-none flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </div>
                                                    <div>
                                                        <h3 id="modal-title" class="font-extrabold text-gray-800 text-2xl">{{ $reportId ? 'Edit Laporan Kejadian' : 'Buat Laporan Kejadian Baru' }}</h3>
                                                        <p class="text-sm text-gray-500 mt-1">Isi semua field yang diperlukan.</p>
                                                    </div>
                                                </div>
                                                <div class="space-y-6">
                                                    <div>
                                                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Kejadian</label>
                                                        <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 11-2 0V4H6a1 1 0 11-2 0V4z" clip-rule="evenodd" /></svg></div><input id="title" type="text" wire:model.lazy="title" placeholder="Contoh: Kerusakan Pintu Sel Blok A" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300"></div>
                                                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div>
                                                            <label for="report_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                                                            <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg></div><input id="report_date" type="date" wire:model.lazy="report_date" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300"></div>
                                                            @error('report_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label for="report_time" class="block text-sm font-bold text-gray-700 mb-2">Waktu</label>
                                                            <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd" /></svg></div><input id="report_time" type="time" wire:model.lazy="report_time" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300"></div>
                                                            @error('report_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div>
                                                            <label for="post_id" class="block text-sm font-bold text-gray-700 mb-2">Pos Jaga (Opsional)</label>
                                                            <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg></div><select id="post_id" wire:model.lazy="post_id" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300"><option value="">Pilih Pos Jaga</option>@foreach($allPosts as $post)<option value="{{ $post->id }}">{{ $post->name }}</option>@endforeach</select></div>
                                                        </div>
                                                        @if ($reportId)
                                                        <div>
                                                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status Laporan</label>
                                                            <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg></div><select id="status" wire:model.lazy="status" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300"><option>Baru</option><option>Ditinjau</option><option>Selesai</option></select></div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <label for="people_involved" class="block text-sm font-bold text-gray-700 mb-2">Pihak Terlibat (Opsional)</label>
                                                        <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg></div><input id="people_involved" type="text" wire:model.lazy="people_involved" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300" placeholder="Nama petugas, warga binaan, dll."></div>
                                                    </div>
                                                    <div>
                                                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Uraian Kejadian</label>
                                                        <div class="relative"><div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pt-3 pl-3.5"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg></div><textarea id="description" wire:model.lazy="description" rows="4" class="block w-full rounded-xl border-gray-300/80 bg-gray-50/80 py-3 pl-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition duration-300 resize-none" placeholder="Jelaskan kejadian secara rinci..."></textarea></div>
                                                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-end gap-4 bg-gray-50/30 px-8 py-5 mt-6 rounded-b-2xl border-t border-white/20">
                                                <button wire:click.prevent="cancel" type="button" class="px-6 py-2.5 text-sm font-bold text-gray-700 bg-transparent rounded-lg hover:bg-red-100/50 hover:text-red-700 transition-all duration-300">Batal</button>
                                                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 active:scale-95">
                                                    <span wire:loading.remove wire:target="save">Simpan Laporan</span>
                                                    <div wire:loading wire:target="save" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyimpan...</span></div>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    