<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User; // Penting: Import Model User
use Carbon\Carbon;

class RosterDashboard extends Component
{
    public $dateRange = [];
    public $startDate;

    // Variabel untuk Modal Popup
    public $isModalOpen = false;
    public $selectedRosterId = null;
    public $selectedRosterName = '';
    public $selectedShiftId = null;

    public function mount()
    {
        $this->startDate = Carbon::today();
        $this->generateDateRange();
    }

    public function generateDateRange()
    {
        $this->dateRange = [];
        // Tampilkan 5 hari ke depan
        for ($i = 0; $i < 5; $i++) {
            $this->dateRange[] = $this->startDate->copy()->addDays($i)->format('Y-m-d');
        }
    }

    public function nextDays()
    {
        $this->startDate->addDays(5);
        $this->generateDateRange();
    }

    public function prevDays()
    {
        $this->startDate->subDays(5);
        $this->generateDateRange();
    }

    // ==========================================
    // FITUR 1: AUTO GENERATE JADWAL (MAGIC BUTTON)
    // ==========================================
    public function generateSchedule()
    {
        // 1. Tentukan target bulan & tahun berdasarkan tanggal yang sedang dilihat
        $targetDate = $this->startDate->copy();
        $month = $targetDate->month;
        $year = $targetDate->year;
        $daysInMonth = $targetDate->daysInMonth;

        // 2. Hapus jadwal lama di bulan tersebut (Reset) agar tidak duplikat
        Roster::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();

        // 3. Ambil semua pegawai
        $users = User::all();

        // 4. Tentukan Pola Shift
        // Asumsi ID Shift di database: 1=Pagi, 2=Siang, 3=Malam, null=Libur
        // Silakan sesuaikan angka ini jika ID di database kamu berbeda
        $pattern = [1, 2, 3, null];

        $rostersToInsert = [];

        // 5. Mulai Perulangan (Looping) untuk setiap Pegawai
        foreach ($users as $index => $user) {

            // Trik Matematika: Offset pola berdasarkan urutan user
            // User 1 mulai pola ke-0, User 2 mulai pola ke-1, dst.
            // Supaya tidak semua orang masuk Pagi di hari yang sama.
            $patternIndex = $index % count($pattern);

            for ($day = 1; $day <= $daysInMonth; $day++) {

                // Ambil Shift ID dari pola saat ini
                $shiftId = $pattern[$patternIndex];

                // Buat tanggal lengkap YYYY-MM-DD
                $currentDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');

                // Jika shiftId tidak null (artinya bukan hari libur), catat jadwalnya
                if ($shiftId) {
                    $rostersToInsert[] = [
                        'user_id' => $user->id,
                        'shift_id' => $shiftId,
                        'date' => $currentDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Putar pola untuk hari berikutnya (0 -> 1 -> 2 -> 3 -> 0 ...)
                $patternIndex = ($patternIndex + 1) % count($pattern);
            }
        }

        // 6. Bulk Insert (Simpan masal per 500 data agar cepat)
        foreach (array_chunk($rostersToInsert, 500) as $chunk) {
            Roster::insert($chunk);
        }

        // 7. Refresh tampilan & Kirim Notifikasi
        $this->generateDateRange();
        $this->dispatch('roster-updated', message: 'Jadwal otomatis berhasil dibuat!');
    }

    // ==========================================
    // FITUR 2: EDIT MANUAL (MODAL POPUP)
    // ==========================================
    public function editRoster($rosterId)
    {
        $roster = Roster::with('user')->find($rosterId);

        if ($roster) {
            $this->selectedRosterId = $roster->id;
            $this->selectedRosterName = $roster->user->name;
            $this->selectedShiftId = $roster->shift_id;
            $this->isModalOpen = true;
        }
    }

    public function saveRoster()
    {
        $roster = Roster::find($this->selectedRosterId);

        if ($roster) {
            $roster->update([
                'shift_id' => $this->selectedShiftId
            ]);
        }

        $this->isModalOpen = false;
        $this->dispatch('roster-updated', message: 'Perubahan jadwal disimpan.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    // ==========================================
    // RENDER VIEW
    // ==========================================
    public function render()
    {
        // 1. Ambil Data Jadwal untuk Kalender
        $rosters = Roster::with(['user', 'shift'])
            ->whereIn('date', $this->dateRange)
            ->get()
            ->groupBy('date');

        // 2. Hitung Statistik Grafik (Donat)
        $shiftStats = Roster::whereMonth('date', $this->startDate->month)
            ->whereYear('date', $this->startDate->year) // Tambahkan tahun biar aman
            ->with('shift')
            ->get()
            ->groupBy('shift.name')
            ->map->count();

        // 3. Hitung Statistik Kartu (Header)
        $todayStats = [
            'total_pegawai' => User::count(),
            'dinas_malam' => Roster::where('date', Carbon::today())
                ->whereHas('shift', fn($q) => $q->where('is_overnight', true))
                ->count(),
            // Off duty = Total Pegawai - Pegawai yang punya jadwal hari ini
            'off_duty' => User::count() - Roster::where('date', Carbon::today())->count()
        ];

        return view('livewire.roster-dashboard', [
            'rosters' => $rosters,
            'shifts' => Shift::all(),
            'shiftStats' => $shiftStats,
            'todayStats' => $todayStats
        ])->layout('components.layouts.app');
    }
}
