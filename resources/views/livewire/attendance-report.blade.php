<div class="min-h-screen bg-gray-100 p-8 font-sans">
    
    <div class="max-w-7xl mx-auto mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">📊 Rekapitulasi Absensi</h1>
            <p class="text-gray-500">Laporan kedisiplinan pegawai per bulan.</p>
        </div>

        {{-- Filter Bulan & Tahun --}}
        <div class="flex gap-3 bg-white p-2 rounded-xl shadow-sm border border-gray-200">
            <select wire:model.live="month" class="border-none bg-transparent focus:ring-0 font-bold text-gray-700 cursor-pointer">
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <select wire:model.live="year" class="border-none bg-transparent focus:ring-0 font-bold text-gray-700 cursor-pointer border-l border-gray-200 pl-3">
                @for($y = 2024; $y <= date('Y'); $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    <div class="max-w-7xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white uppercase text-xs font-bold tracking-wider">
                        <th class="py-4 px-6">Pegawai</th>
                        <th class="py-4 px-6 text-center bg-green-600">Hadir Tepat Waktu</th>
                        <th class="py-4 px-6 text-center bg-yellow-600">Terlambat</th>
                        <th class="py-4 px-6 text-center bg-red-600">Tanpa Keterangan</th>
                        <th class="py-4 px-6 text-center bg-gray-700">Total Masuk</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($report as $userId => $data)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-6">
                            <p class="font-bold text-gray-800">{{ $data['name'] }}</p>
                            <p class="text-xs text-gray-400">{{ $data['nip'] }}</p>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-green-600 bg-green-50">
                            {{ $data['hadir'] }} Hari
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-yellow-600 bg-yellow-50">
                            {{ $data['terlambat'] }} Kali
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-red-600 bg-red-50">
                            {{ $data['alpha'] }} Kali
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-gray-800">
                            {{ $data['total_kehadiran'] }} Hari
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>