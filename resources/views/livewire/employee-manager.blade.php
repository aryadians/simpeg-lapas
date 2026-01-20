<div class="min-h-screen bg-gray-100 p-8 font-sans">

    {{-- Header Section --}}
    <div class="max-w-7xl mx-auto mb-8 flex justify-between items-center animate-fade-in-down">
        <div>
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                Manajemen Pegawai
            </h1>
            <p class="text-gray-500">Database Personel Lapas</p>
        </div>
        <button wire:click="create" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transform transition hover:-translate-y-1 flex items-center gap-2">
            <span>+ Tambah Pegawai</span>
        </button>
    </div>

    {{-- Tabel Glassmorphism --}}
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50 text-indigo-900 uppercase text-sm leading-normal">
                        <th class="py-4 px-6 font-bold">Nama / NIP</th>
                        <th class="py-4 px-6 font-bold">Jabatan</th>
                        <th class="py-4 px-6 font-bold text-center">Grade</th>
                        <th class="py-4 px-6 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($employees as $employee)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-4 px-6 text-left whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-gradient-to-tr from-purple-500 to-indigo-500 text-white rounded-full h-10 w-10 flex items-center justify-center font-bold shadow-md">
                                    {{ substr($employee->name, 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <span class="block font-bold text-gray-800">{{ $employee->name }}</span>
                                    <span class="block text-xs text-gray-500">{{ $employee->nip }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-left">
                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">{{ $employee->jabatan }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="font-bold text-gray-700">{{ $employee->grade }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex item-center justify-center gap-3">
                                <button wire:click="edit({{ $employee->id }})" class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition">
                                    ✏️
                                </button>
                                <button wire:click="delete({{ $employee->id }})" onclick="confirm('Yakin hapus?') || event.stopImmediatePropagation()" class="w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL FORM (Sama gayanya dengan Roster) --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative animate-bounce-in">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">
                {{ $employeeId ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                        <input wire:model="nip" type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input wire:model="email" type="email" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <input wire:model="jabatan" type="text" placeholder="Ex: Anggota Jaga" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade Tukin</label>
                        <input wire:model="grade" type="number" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 gap-3">
                <button wire:click="closeModal" class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">Batal</button>
                <button wire:click="store" class="px-5 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg font-medium shadow-lg transition">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>