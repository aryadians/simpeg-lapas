<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden mb-8 animate-fade-in-down">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Laporan Tunjangan Kinerja</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kalkulasi potongan Tukin berdasarkan absensi bulan <span class="font-bold text-indigo-500">{{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</span>.</p>
                    </div>
                     <div class="flex items-center space-x-3">
                        <input type="month" id="month-selector" wire:model.lazy="selectedMonth" class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                        <button wire:click="generateReport" wire:loading.attr="disabled" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-transform transform hover:scale-105 whitespace-nowrap">
                            <span wire:loading.remove wire:target="generateReport">Refresh Data</span>
                            <span wire:loading wire:target="generateReport">Memuat...</span>
                        </button>
                        <button onclick="window.open('{{ route('tukin.report.pdf', ['month' => $selectedMonth]) }}', '_blank')" wire:loading.attr="disabled" class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-transform transform hover:scale-105 whitespace-nowrap flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
                            <span>Cetak PDF</span>
                        </button>
                        <button wire:click="exportCsv" wire:loading.attr="disabled" class="px-5 py-2.5 bg-sky-600 text-white font-semibold rounded-lg shadow-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-transform transform hover:scale-105 whitespace-nowrap flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                           </svg>
                            <span wire:loading.remove wire:target="exportCsv">Ekspor CSV</span>
                            <span wire:loading wire:target="exportCsv">Mengekspor...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employee Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($reportData as $userReport)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:scale-[1.03] transform animate-pop-in" style="--delay: {{ $loop->index * 100 }}ms">
                    {{-- Card Header --}}
                    <div class="p-5 border-b-2 border-dashed dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $userReport['name'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $userReport['jabatan'] }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 font-mono">NIP: {{ $userReport['nip'] }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm text-gray-500 dark:text-gray-300">Grade</p>
                                <p class="text-2xl font-extrabold text-indigo-500">{{ $userReport['grade'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Financials --}}
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-300">Tukin Pokok</span>
                            <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Rp {{ number_format($userReport['tukin_nominal'], 0, ',', '.') }}</span>
                        </div>
                         <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600 dark:text-gray-300">Potongan ({{ $userReport['total_deduction_percentage'] }}%)</span>
                            <span class="font-bold text-lg text-red-500">- Rp {{ number_format($userReport['total_deduction_amount'], 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg text-gray-800 dark:text-white">Tukin Diterima</span>
                            <span class="font-extrabold text-2xl text-green-500">Rp {{ number_format($userReport['final_tukin'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Attendance Details Accordion --}}
                    <div x-data="{ open: false }" class="border-t dark:border-gray-700">
                        <button @click="open = !open" class="w-full px-5 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex justify-between items-center">
                            <span>Rincian Absensi Bulan Ini</span>
                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="max-h-52 overflow-y-auto bg-gray-50/50 dark:bg-gray-900/50">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-800 dark:text-gray-400 sticky top-0">
                                    <tr>
                                        <th scope="col" class="px-4 py-2">Tanggal</th>
                                        <th scope="col" class="px-4 py-2">Status</th>
                                        <th scope="col" class="px-4 py-2">Potongan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userReport['attendances'] as $att)
                                        @if($att['deduction'] > 0)
                                        <tr class="border-b dark:border-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
                                            <td class="px-4 py-1.5">{{ $att['date'] }}</td>
                                            <td class="px-4 py-1.5 font-medium">
                                                @if ($att['status'] == 'terlambat')
                                                    <span class="text-yellow-500">{{ ucfirst($att['status']) }}</span>
                                                @elseif ($att['status'] == 'alpha')
                                                    <span class="text-red-500">{{ ucfirst($att['status']) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-1.5 text-red-500">{{ $att['deduction'] }}%</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 col-span-full py-16">
                    Tidak ada data pegawai untuk diproses. Silakan tambahkan data nominal tukin di menu "Data Pegawai".
                </p>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
@endpush
