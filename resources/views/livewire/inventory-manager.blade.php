<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow animate__animated animate__fadeInDown">
                {{ session('message') }}
            </div>
        @endif

        {{-- Header --}}
        <header class="mb-6 animate__animated animate__fadeInDown">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800">Manajemen Inventaris</h1>
                    <p class="text-gray-500 mt-1">Lacak aset dan perlengkapan penting.</p>
                </div>
                <button wire:click="openModal()" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition-all duration-300 hover:shadow-indigo-500/50 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>Tambah Item</span>
                </button>
            </div>
        </header>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-100/80 animate__animated animate__fadeInUp">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Barang</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Peminjam</th>
                            <th scope="col" class="px-6 py-3">Tgl Pinjam</th>
                            <th scope="col" class="px-6 py-3">Jatuh Tempo</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $item)
                            <tr wire:key="{{ $item->id }}" class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $item->name }}
                                    <p class="text-xs text-gray-400">{{ $item->serial_number }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->status == 'available')
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Tersedia</span>
                                    @elseif ($item->status == 'checked_out')
                                        <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">Dipinjam</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $item->holder->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $item->checked_out_at ? $item->checked_out_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="px-6 py-4">{{ $item->due_at ? $item->due_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    @if ($item->status == 'available')
                                        <button wire:click="openCheckoutModal({{ $item->id }})" class="font-medium text-indigo-600 hover:underline">Pinjamkan</button>
                                    @elseif ($item->status == 'checked_out')
                                        <button wire:click="checkin({{ $item->id }})" wire:confirm="Anda yakin ingin mengembalikan item ini?" class="font-medium text-green-600 hover:underline">Kembalikan</button>
                                    @endif
                                    <button wire:click="openHistoryModal({{ $item->id }})" class="font-medium text-gray-600 hover:underline">Riwayat</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    Belum ada data inventaris.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>

    {{-- Add Item Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-start justify-center p-4 pt-16">
        <div wire:click="closeModal" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative">
            <form wire:submit="store">
                <div class="p-6 border-b">
                    <h2 class="text-2xl font-bold text-gray-800">Tambah Item Baru</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Item</label>
                        <input wire:model="name" type="text" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5" placeholder="Contoh: Radio HT">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Seri (Opsional)</label>
                        <input wire:model="serial_number" type="text" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5" placeholder="Contoh: SN12345">
                        @error('serial_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5" placeholder="Detail atau kondisi barang"></textarea>
                    </div>
                </div>
                <div class="flex justify-end p-6 gap-3 bg-gray-50 rounded-b-2xl">
                    <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-gray-700 bg-gray-200/80 rounded-xl">Batal</button>
                    <button type="submit" class="px-6 py-2.5 text-white bg-indigo-600 rounded-xl font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Checkout Modal --}}
    @if($isCheckoutModalOpen)
    <div class="fixed inset-0 z-[100] flex items-start justify-center p-4 pt-16">
         <div wire:click="closeCheckoutModal" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
         <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative">
            <form wire:submit="checkout">
                <div class="p-6 border-b">
                    <h2 class="text-2xl font-bold text-gray-800">Pinjamkan: {{ $selectedItem?->name }}</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pinjamkan Kepada</label>
                        <select wire:model="selectedUser" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5">
                            <option value="">Pilih Pegawai</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedUser') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jatuh Tempo (Opsional)</label>
                        <input wire:model="due_at" type="datetime-local" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan (Opsional)</label>
                        <textarea wire:model="notes" rows="3" class="w-full bg-gray-100 border-transparent rounded-lg p-2.5" placeholder="Catatan saat serah terima"></textarea>
                    </div>
                </div>
                <div class="flex justify-end p-6 gap-3 bg-gray-50 rounded-b-2xl">
                    <button type="button" wire:click="closeCheckoutModal" class="px-5 py-2.5 text-gray-700 bg-gray-200/80 rounded-xl">Batal</button>
                    <button type="submit" class="px-6 py-2.5 text-white bg-indigo-600 rounded-xl font-bold">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @endif

     {{-- History Modal --}}
    @if($isHistoryModalOpen)
    <div class="fixed inset-0 z-[100] flex items-start justify-center p-4 pt-16">
         <div wire:click="closeHistoryModal" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
         <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Riwayat: {{ $selectedItem?->name }}</h2>
                <button wire:click="closeHistoryModal" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6">
                <ul class="space-y-4">
                    @forelse($selectedItem->logs as $log)
                        <li class="flex gap-4">
                            <div class="flex-shrink-0">
                                @if($log->action == 'checked_out')
                                    <span class="h-10 w-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                    </span>
                                @else
                                    <span class="h-10 w-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                         <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">
                                    @if($log->action == 'checked_out')
                                        Dipinjam oleh {{ $log->user->name }}
                                    @else
                                        Dikembalikan oleh {{ $log->user->name }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">{{ $log->action_at->format('d M Y, H:i') }}</p>
                                @if($log->notes)
                                    <p class="text-sm text-gray-600 mt-1 italic">"{{ $log->notes }}"</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-gray-500 py-4">Belum ada riwayat untuk item ini.</li>
                    @endforelse
                </ul>
            </div>
             <div class="p-4 bg-gray-50 rounded-b-2xl text-right">
                <button type="button" wire:click="closeHistoryModal" class="px-5 py-2.5 text-gray-700 bg-gray-200/80 rounded-xl">Tutup</button>
            </div>
        </div>
    </div>
    @endif

</div>
