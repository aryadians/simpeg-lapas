<div class="min-h-screen bg-gray-100 p-8 font-sans">

    {{-- BAGIAN 1: HEADER & TOOLS --}}
    <div class="max-w-7xl mx-auto mb-6 flex flex-col md:flex-row justify-between items-center gap-4 animate-fade-in-down">
        
        {{-- Judul --}}
        <div>
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                Manajemen Pegawai
            </h1>
            <p class="text-gray-500">Database Personel Lapas</p>
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
                       class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition" 
                       placeholder="Cari Nama / NIP...">
            </div>

            {{-- Tombol Tambah --}}
            <button wire:click="create" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-1 flex items-center gap-2 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tambah Pegawai</span>
            </button>
        </div>
    </div>

    {{-- BAGIAN 2: TABEL GLASSMORPHISM --}}
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-fade-in-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50 text-indigo-900 uppercase text-xs font-bold tracking-wider leading-normal">
                        <th class="py-4 px-6">Nama / NIP</th>
                        <th class="py-4 px-6">Jabatan</th>
                        <th class="py-4 px-6 text-center">Grade</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($employees as $employee)
                    <tr wire:key="{{ $employee->id }}" class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                        
                        {{-- Kolom Nama & Foto --}}
                        <td class="py-4 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-gradient-to-tr from-purple-500 to-indigo-500 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold shadow-md shrink-0">
                                    {{ substr($employee->name, 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <span class="block font-bold text-gray-800 text-base">{{ $employee->name }}</span>
                                    <span class="block text-xs text-gray-500 font-mono tracking-wide">{{ $employee->nip }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Jabatan --}}
                        <td class="py-4 px-6 text-left">
                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-bold">
                                {{ $employee->jabatan }}
                            </span>
                        </td>

                        {{-- Kolom Grade --}}
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 font-bold text-gray-700">
                                {{ $employee->grade }}
                            </div>
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="py-4 px-6 text-center">
                            <div class="flex item-center justify-center gap-3">
                                <button wire:click="edit({{ $employee->id }})" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 hover:scale-110 flex items-center justify-center transition shadow-sm" title="Edit">
                                    ✏️
                                </button>
                                
                                <button wire:click="delete({{ $employee->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus pegawai ini? Data yang dihapus tidak bisa dikembalikan."
                                        class="w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 flex items-center justify-center transition shadow-sm" title="Hapus">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-4xl mb-2">🔍</span>
                                <p>Data pegawai tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination (Jika ada banyak data) --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $employees->links() }}
        </div>
    </div>

    {{-- BAGIAN 3: MODAL FORM --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity"
         x-data x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100">
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 relative border border-white/20">
            
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $employeeId ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}
                </h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>
            
            <form wire:submit="store">
                <div class="space-y-5">
                    
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Nama Pegawai">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Grid NIP & Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">NIP</label>
                            <input wire:model="nip" type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="19xxxxxxxx">
                            @error('nip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="email@lapas.go.id">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Grid Jabatan & Grade --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan</label>
                            <input wire:model="jabatan" type="text" placeholder="Ex: Anggota Jaga" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('jabatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Grade Tukin</label>
                            <input wire:model="grade" type="number" class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('grade') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end mt-8 gap-3">
                    <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl font-bold shadow-lg transform transition hover:-translate-y-0.5 flex items-center">
                        <span wire:loading.remove wire:target="store">Simpan Data</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>