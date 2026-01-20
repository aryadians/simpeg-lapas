<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    // FITUR 1: AUTO GENERATE JADWAL (SECURE & SMART)
    // ==========================================
    public function generateSchedule()
    {
        // KEAMANAN: Cek Role
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('roster-updated', message: 'AKSES DITOLAK: Anda bukan Admin!');
            return;
        }

        $targetDate = $this->startDate->copy();
        $month = $targetDate->month;
        $year = $targetDate->year;
        $daysInMonth = $targetDate->daysInMonth;

        // 1. Hapus jadwal lama (Reset)
        Roster::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();

        // 2. Ambil pegawai (Acak urutan agar adil)
        $users = User::inRandomOrder()->get();

        // 3. Pola Shift Dasar: Pagi, Siang, Malam, Libur
        $basePattern = [1, 2, 3, null];

        $rostersToInsert = [];

        foreach ($users as $user) {

            // Setiap pegawai mulai dari titik pola yang acak
            $patternIndex = rand(0, count($basePattern) - 1);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $shiftId = null;

                // --- LOGIKA KHUSUS TESTING ---
                // Jika User ini adalah SAYA (Admin) DAN Tanggal adalah HARI INI
                // Maka PAKSA masuk "Regu Pagi" (ID 1) agar tombol absen muncul.
                if ($user->id === Auth::id() && $currentDate === Carbon::today()->format('Y-m-d')) {
                    $shiftId = 1;
                } else {
                    // --- LOGIKA NORMAL ---

                    // Cek Cuti
                    $isOnLeave = LeaveRequest::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate)
                        ->exists();

                    if ($isOnLeave) {
                        // Jika cuti, lewati simpan jadwal, tapi putar pola
                        $patternIndex = ($patternIndex + 1) % count($basePattern);
                        continue;
                    }

                    $shiftId = $basePattern[$patternIndex];
                }

                // Jika Shift ID ada (bukan null/Libur), masukkan ke antrian simpan
                if ($shiftId) {
                    $rostersToInsert[] = [
                        'user_id' => $user->id,
                        'shift_id' => $shiftId,
                        'date' => $currentDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Putar ke pola shift berikutnya untuk besok
                $patternIndex = ($patternIndex + 1) % count($basePattern);
            }
        }

        // Simpan Data (Bulk Insert agar cepat)
        foreach (array_chunk($rostersToInsert, 500) as $chunk) {
            Roster::insert($chunk);
        }

        // Refresh Tampilan
        $this->generateDateRange();
        $this->dispatch('roster-updated', message: 'Jadwal baru berhasil diacak & dibuat! (Admin dipaksa masuk hari ini)');
    }

    // ==========================================
    // FITUR 2: EDIT MANUAL (SECURE MODAL)
    // ==========================================
    public function editRoster($rosterId)
    {
        // KEAMANAN: Staff tidak boleh buka modal edit
        if (Auth::user()->role !== 'admin') {
            return;
        }

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
        // KEAMANAN: Double Check saat simpan
        if (Auth::user()->role !== 'admin') {
            return;
        }

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

        // 2. Statistik Grafik (Mengikuti Bulan Kalender)
        $shiftStats = Roster::whereMonth('date', $this->startDate->month)
            ->whereYear('date', $this->startDate->year)
            ->with('shift')
            ->get()
            ->groupBy('shift.name')
            ->map->count();

        // 3. Statistik Kartu (Realtime Hari Ini)
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
