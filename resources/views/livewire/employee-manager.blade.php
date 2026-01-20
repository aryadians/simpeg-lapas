<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- BAGIAN 1: HEADER & TOOLS --}}
        <header class="mb-8 animate__animated animate__fadeInDown">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                {{-- Judul --}}
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        Manajemen Pegawai
                    </h1>
                    <p class="text-gray-500 mt-1">Database Personel Lapas Kelas IIB Purwakarta</p>
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative w-full md:w-64">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="pl-10 pr-4 py-2.5 w-full border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white shadow-sm transition-all duration-300" 
                               placeholder="Cari Nama / NIP...">
                    </div>

                    {{-- Tombol Tambah --}}
                    <button wire:click="create" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-1 flex items-center gap-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>
        </header>

        {{-- BAGIAN 2: GRID KARTU PEGAWAI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate__animated animate__fadeInUp">
            @forelse($employees as $employee)
            <div wire:key="{{ $employee->id }}" class="bg-white rounded-2xl shadow-md border border-gray-100/80 hover:shadow-xl hover:border-indigo-200/50 transition-all duration-300 group">
                <div class="p-6">
                    <div class="flex items-start gap-5">
                        {{-- Avatar --}}
                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 rounded-xl h-14 w-14 flex items-center justify-center font-black text-2xl shadow-md shrink-0">
                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                        </div>
                        {{-- Info Utama --}}
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $employee->name }}</h3>
                            <p class="text-xs text-gray-500 font-mono tracking-wide">{{ $employee->nip }}</p>
                             <div class="mt-2">
                                <span class="bg-indigo-50 text-indigo-700 py-1 px-3 rounded-full text-xs font-bold">
                                    {{ $employee->jabatan }}
                                </span>
                            </div>
                        </div>
                        
                    </div>
                </div>
                 {{-- Footer Kartu (Aksi) --}}
                <div class="bg-gray-50/70 px-6 py-3 rounded-b-2xl flex justify-end items-center gap-2 border-t border-gray-100">
                    <button wire:click="resetPassword({{ $employee->id }})" class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 flex items-center justify-center transition-all duration-200" title="Reset Password">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                    </button>
                    <button wire:click="edit({{ $employee->id }})" class="w-9 h-9 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Edit Data">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                    </button>
                    <button wire:click="delete({{ $employee->id }})" class="w-9 h-9 rounded-full bg-red-100 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all duration-200" title="Hapus Pegawai">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700">Data Pegawai Tidak Ditemukan</h3>
                    <p class="mt-1">Coba kata kunci lain atau tambahkan pegawai baru.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $employees->links() }}
        </div>
    </div>

    {{-- BAGIAN 3: MODAL FORM --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div wire:click="closeModal" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
        
        {{-- Modal Panel --}}
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative animate__animated animate__zoomIn animate__faster">
            <form wire:submit="store" class="flex flex-col h-full">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $employeeId ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}
                    </h2>
                    <button wire:click="closeModal" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                {{-- Modal Body --}}
                <div class="p-6 space-y-5 flex-1 overflow-y-auto">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="Nama Lengkap Pegawai">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">NIP</label>
                            <input wire:model="nip" type="text" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="19xxxxxxxxxxxxxx">
                            @error('nip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="email@lapas.go.id">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan</label>
                            <input wire:model="jabatan" type="text" placeholder="Ex: Anggota Jaga" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('jabatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Grade Tukin</label>
                            <input wire:model="grade" type="number" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="Contoh: 5">
                            @error('grade') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end p-6 gap-3 bg-gray-50 rounded-b-2xl border-t border-gray-100">
                    <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-gray-700 bg-gray-200/80 hover:bg-gray-300 rounded-xl font-medium transition-colors duration-300">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-0.5 flex items-center justify-center min-w-[120px]">
                        <div wire:loading.remove wire:target="store">
                            Simpan
                        </div>
                        <div wire:loading wire:target="store" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Menyimpan</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>