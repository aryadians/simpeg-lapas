<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- BAGIAN 1: HEADER & TOOLS --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center lg:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Personnel <span class="text-indigo-600">Assets</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Database Manajemen Kepegawaian</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto relative z-10">
                    {{-- Search Bar --}}
                    <div class="relative group">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-500 text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="pl-12 pr-6 py-3.5 w-full sm:w-72 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 shadow-inner transition-all duration-300 font-bold text-sm placeholder-gray-300" 
                               placeholder="Search Identity...">
                    </div>

                    {{-- Tombol Tambah --}}
                    <button wire:click="create" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" stroke-width="1">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>New Staff</span>
                    </button>
                </div>
            </div>
        </header>

        {{-- BAGIAN 2: GRID KARTU PEGAWAI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 animate__animated animate__fadeInUp">
            @forelse($employees as $employee)
            <div wire:key="{{ $employee->id }}" class="bg-white rounded-[2rem] shadow-lg border border-gray-100 transition-all duration-500 group hover:shadow-2xl hover:border-indigo-200 relative overflow-hidden">
                <div class="p-8">
                    <div class="flex items-start gap-6">
                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-2xl h-16 w-16 flex items-center justify-center font-black text-2xl shadow-xl shadow-indigo-500/20 transform group-hover:-rotate-6 transition-transform duration-500">
                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                            </div>
                            @if($employee->isOnline())
                                <span class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 border-4 border-white rounded-full shadow-sm animate-pulse"></span>
                            @endif
                        </div>
                        {{-- Info Utama --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-black text-gray-900 text-xl leading-tight truncate group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $employee->name }}</h3>
                            <p class="text-[10px] text-gray-400 font-black font-mono tracking-[0.2em] mt-1 flex items-center gap-1.5 uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm5 3a2 2 0 100-4 2 2 0 000 4z" /></svg>
                                {{ $employee->nip }}
                            </p>
                             <div class="mt-5 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="bg-indigo-50 text-indigo-700 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-wider border border-indigo-100">
                                        {{ $employee->jabatan }}
                                    </span>
                                    <span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-wider border border-slate-200">
                                        Grade {{ $employee->grade }}
                                    </span>
                                </div>
                                <div class="text-sm font-black text-emerald-600 flex items-center gap-1.5 pt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                                    Rp {{ number_format($employee->tukin_nominal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 {{-- Footer Kartu (Aksi) --}}
                <div class="bg-gray-50/50 px-8 py-4 flex justify-end items-center gap-2 border-t border-gray-100/50">
                    <button wire:click="resetPassword({{ $employee->id }})" class="w-10 h-10 rounded-xl bg-white text-gray-400 hover:text-indigo-600 hover:shadow-md border border-gray-100 flex items-center justify-center transition-all duration-300" title="Reset Access">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                    </button>
                    <button wire:click="edit({{ $employee->id }})" class="w-10 h-10 rounded-xl bg-white text-gray-400 hover:text-amber-600 hover:shadow-md border border-gray-100 flex items-center justify-center transition-all duration-300" title="Modify Record">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button wire:click="delete({{ $employee->id }})" class="w-10 h-10 rounded-xl bg-white text-gray-400 hover:text-rose-600 hover:shadow-md border border-gray-100 flex items-center justify-center transition-all duration-300" title="Purge Record">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="flex flex-col items-center justify-center opacity-40 grayscale">
                    <span class="text-7xl mb-6">📂</span>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">No Records Found</h3>
                    <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em]">Try adjusting your search filters.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="mt-12">
            {{ $employees->links() }}
        </div>
    </div>

    {{-- BAGIAN 3: MODAL FORM --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div wire:click="closeModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
        
        {{-- Modal Panel --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative animate__animated animate__zoomIn animate__faster overflow-hidden border border-white/20">
            <form wire:submit="store" class="flex flex-col h-full">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-8 border-b border-gray-50 bg-gray-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                            {{ $employeeId ? 'Modify Staff' : 'Onboard Personnel' }}
                        </h2>
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-1">Data Asset Entry</p>
                    </div>
                    <button wire:click="closeModal" type="button" class="w-12 h-12 rounded-2xl bg-white shadow-sm hover:bg-gray-100 text-gray-400 flex items-center justify-center transition-all border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                {{-- Modal Body --}}
                <div class="p-8 space-y-6 flex-1 overflow-y-auto no-scrollbar">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Legal Name</label>
                        <input wire:model="name" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="Identity Name">
                        @error('name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Personnel ID (NIP)</label>
                            <input wire:model="nip" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold font-mono" placeholder="19xxxxxxxx">
                            @error('nip') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Digital Mail</label>
                            <input wire:model="email" type="email" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="staff@lapas.go.id">
                            @error('email') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Operational Assignment</label>
                        <input wire:model="jabatan" type="text" placeholder="Designation Title" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                        @error('jabatan') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tukin Grade</label>
                            <input wire:model="grade" type="number" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="Rank Level">
                            @error('grade') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nominal Allowance</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 font-bold text-xs">Rp</div>
                                <input wire:model="tukin_nominal" type="number" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 pl-10 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="0">
                            </div>
                            @error('tukin_nominal') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center">
                    <button type="submit" class="w-full py-4 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-xl shadow-indigo-500/30 transform transition-all active:scale-95 flex items-center justify-center uppercase tracking-[0.2em] text-xs">
                        <div wire:loading.remove wire:target="store">
                            {{ $employeeId ? 'Commit Updates' : 'Confirm Onboarding' }}
                        </div>
                        <div wire:loading wire:target="store">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
