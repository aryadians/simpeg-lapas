<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use App\Models\LeaveRequest; // PENTING: Import Model Cuti
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
    // FITUR 1: AUTO GENERATE JADWAL (DENGAN CEK CUTI)
    // ==========================================
    public function generateSchedule()
    {
        // 1. Tentukan target bulan & tahun berdasarkan tanggal yang sedang dilihat
        $targetDate = $this->startDate->copy();
        $month = $targetDate->month;
        $year = $targetDate->year;
        $daysInMonth = $targetDate->daysInMonth;

        // 2. Hapus jadwal lama di bulan tersebut (Reset)
        Roster::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();

        // 3. Ambil semua pegawai
        $users = User::all();

        // 4. Pola Shift: 1=Pagi, 2=Siang, 3=Malam, null=Libur
        $pattern = [1, 2, 3, null];

        $rostersToInsert = [];

        // 5. Mulai Perulangan
        foreach ($users as $index => $user) {

            // Offset pola agar shift tidak seragam
            $patternIndex = $index % count($pattern);

            for ($day = 1; $day <= $daysInMonth; $day++) {

                // Buat tanggal YYYY-MM-DD
                $currentDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');

                // --- LOGIKA UTAMA: CEK CUTI ---
                // Cek apakah user sedang cuti (approved) di tanggal ini
                $isOnLeave = LeaveRequest::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->exists();

                if ($isOnLeave) {
                    // Jika Cuti: Jangan buat jadwal (Lewati),
                    // TAPI tetap putar pola agar urutan shift tidak rusak
                    $patternIndex = ($patternIndex + 1) % count($pattern);
                    continue; // Lanjut ke hari berikutnya
                }
                // ------------------------------

                // Ambil Shift ID dari pola
                $shiftId = $pattern[$patternIndex];

                if ($shiftId) {
                    $rostersToInsert[] = [
                        'user_id' => $user->id,
                        'shift_id' => $shiftId,
                        'date' => $currentDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Putar pola untuk hari berikutnya
                $patternIndex = ($patternIndex + 1) % count($pattern);
            }
        }

        // 6. Simpan Data
        foreach (array_chunk($rostersToInsert, 500) as $chunk) {
            Roster::insert($chunk);
        }

        // 7. Refresh
        $this->generateDateRange();
        $this->dispatch('roster-updated', message: 'Jadwal otomatis berhasil dibuat (Pegawai Cuti dilewati)!');
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
        // 1. Data Jadwal Kalender
        $rosters = Roster::with(['user', 'shift'])
            ->whereIn('date', $this->dateRange)
            ->get()
            ->groupBy('date');

        // 2. Statistik Grafik (FIX: Mengikuti Bulan Kalender)
        $shiftStats = Roster::whereMonth('date', $this->startDate->month)
            ->whereYear('date', $this->startDate->year)
            ->with('shift')
            ->get()
            ->groupBy('shift.name')
            ->map->count();

        // 3. Statistik Kartu
        $todayStats = [
            'total_pegawai' => User::count(),
            'dinas_malam' => Roster::where('date', Carbon::today())
                ->whereHas('shift', fn($q) => $q->where('is_overnight', true))
                ->count(),
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