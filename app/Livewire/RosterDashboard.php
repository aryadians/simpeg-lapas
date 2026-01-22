<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Roster;
use App\Models\Shift;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RosterDashboard extends Component
{
    public $dateRange = [];
    public $startDate;

    // Variabel untuk Modal Popup
    public $isModalOpen = false;
    public $isEditMode = false;
    public $rosterId = null;
    public $userId = null;
    public $shiftId = null;
    public $date = null;
    public $rosterUserName = '';

    #[On('attendance-changed')]
    public function refreshDashboard()
    {
        // This empty method, when triggered, will cause the component to re-render.
    }

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
    #[On('generate-schedule-confirmed')]
    public function generateSchedule()
    {
        // KEAMANAN: Cek Role
        if (strtolower(trim(Auth::user()->role)) !== 'admin') {
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

        // 2. Ambil pegawai. Admin (user saat ini) dipisahkan agar bisa di-override.
        $adminUser = Auth::user();
        $otherUsers = User::where('id', '!=', $adminUser->id)->inRandomOrder()->get();
        $users = $otherUsers->prepend($adminUser);

        // 3. Pola Shift Dasar: Pagi, Siang, Malam, Libur
        $basePattern = [1, 2, 3, null];

        $rostersToInsert = [];

        foreach ($users as $user) {

            // Setiap pegawai mulai dari titik pola yang acak
            $patternIndex = rand(0, count($basePattern) - 1);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::createFromDate($year, $month, $day);
                $currentDateString = $currentDate->format('Y-m-d');
                $shiftId = null;

                // --- LOGIKA KHUSUS TESTING ---
                // Jika User ini adalah SAYA (Admin) DAN Tanggal adalah HARI INI
                // Maka PAKSA masuk "Regu Pagi" (ID 1) agar tombol absen muncul.
                if ($user->id === $adminUser->id && $currentDate->isToday()) {
                    $shiftId = 1; // ID 1 adalah Regu Pagi
                } else {
                    // --- LOGIKA NORMAL ---

                    // Cek Cuti
                    $isOnLeave = LeaveRequest::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->where('start_date', '<=', $currentDateString)
                        ->where('end_date', '>=', $currentDateString)
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
                        'date' => $currentDateString,
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
        $this->dispatch('flash-message', type: 'success', title: 'Berhasil!', text: 'Jadwal baru berhasil diacak & dibuat!');
        $this->dispatch('roster-updated', message: 'Jadwal baru berhasil diacak & dibuat!');
    }

    // ==========================================
    // FITUR 2: CRUD JADWAL MANUAL
    // ==========================================
    private function resetForm()
    {
        $this->isModalOpen = false;
        $this->isEditMode = false;
        $this->rosterId = null;
        $this->userId = null;
        $this->shiftId = null;
        $this->date = null;
        $this->rosterUserName = '';
    }

    public function create()
    {
        // KEAMANAN: Hanya admin
        if (strtolower(trim(Auth::user()->role)) !== 'admin') return;
        
        $this->resetForm();
        $this->isModalOpen = true;
        $this->date = $this->startDate->format('Y-m-d'); // Default to current view start date
    }

    public function editRoster($rosterId)
    {
        // KEAMANAN: Hanya admin
        if (strtolower(trim(Auth::user()->role)) !== 'admin') return;

        $roster = Roster::with('user')->find($rosterId);

        if ($roster) {
            $this->resetForm();
            $this->isEditMode = true;
            $this->rosterId = $roster->id;
            $this->userId = $roster->user_id;
            $this->shiftId = $roster->shift_id;
            $this->date = Carbon::parse($roster->date)->format('Y-m-d');
            $this->rosterUserName = $roster->user->name;
            $this->isModalOpen = true;
        }
    }

    public function save()
    {
        if (strtolower(trim(Auth::user()->role)) !== 'admin') return;

        $rules = [
            'userId' => 'required|exists:users,id',
            'shiftId' => 'required|exists:shifts,id',
            'date' => 'required|date',
        ];

        // Unique rule for creating new roster
        if (!$this->isEditMode) {
            $rules['date'] = [
                'required',
                'date',
                // Custom rule to check for uniqueness
                function ($attribute, $value, $fail) {
                    if (Roster::where('user_id', $this->userId)->where('date', $value)->exists()) {
                        $fail('Pegawai ini sudah memiliki jadwal pada tanggal tersebut.');
                    }
                },
            ];
        }
        
        $this->validate($rules);

        $data = [
            'user_id' => $this->userId,
            'shift_id' => $this->shiftId,
            'date' => $this->date,
        ];

        if ($this->isEditMode) {
            Roster::find($this->rosterId)->update($data);
            $message = 'Jadwal berhasil diperbarui.';
        } else {
            Roster::create($data);
            $message = 'Jadwal baru berhasil ditambahkan.';
        }

        $this->resetForm();
        $this->dispatch('flash-message', type: 'success', title: 'Berhasil!', text: $message);
        $this->dispatch('roster-updated');
    }
    
    public function delete($rosterId)
    {
        if (strtolower(trim(Auth::user()->role)) !== 'admin') return;
        $this->dispatch('confirm-dialog', title: 'Hapus Jadwal?', text: 'Anda yakin ingin menghapus jadwal ini secara permanen?', confirm_event: 'delete-roster-confirmed', confirm_params: $rosterId);
    }

    #[On('delete-roster-confirmed')]
    public function deleteConfirmed($rosterId)
    {
        if (strtolower(trim(Auth::user()->role)) !== 'admin') return;
        
        Roster::find($rosterId)->delete();
        $this->dispatch('flash-message', text: 'Jadwal telah dihapus.');
        $this->dispatch('roster-updated');
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    // ==========================================
    // RENDER VIEW
    // ==========================================
    public function render()
    {
        $today = Carbon::today();
        
        // 1. Data Jadwal Kalender
        $rosters = Roster::with(['user', 'shift'])
            ->whereIn('date', $this->dateRange)
            ->get()
            ->groupBy('date');

        // 2. Statistik Grafik Shift (Mengikuti Bulan Kalender)
        $shiftStats = Roster::whereMonth('date', $this->startDate->month)
            ->whereYear('date', $this->startDate->year)
            ->with('shift')
            ->get()
            ->groupBy('shift.name')
            ->map->count();

        // 3. Statistik Kartu (Realtime Hari Ini)
        $totalPegawai = User::count();
        $hadirHariIni = Attendance::whereDate('date', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->distinct('user_id')
            ->count();
            
        $cutiHariIni = LeaveRequest::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->distinct('user_id')
            ->count();

        $todayStats = [
            'total_pegawai' => $totalPegawai,
            'dinas_malam' => Roster::whereDate('date', $today)
                ->whereHas('shift', fn($q) => $q->where('is_overnight', true))
                ->count(),
            'hadir_hari_ini' => $hadirHariIni,
            'cuti_hari_ini' => $cutiHariIni,
            'alpha_hari_ini' => $totalPegawai - $hadirHariIni - $cutiHariIni,
        ];
        
        // 4. Data Untuk Attendance Widget
        $user = Auth::user();
        $now = Carbon::now();
        $todaysRosterForUser = null;

        $roster = Roster::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with('shift')
            ->first();

        if (!$roster && $now->hour < 8) {
            $yesterday = Carbon::yesterday();
            $rosterYesterday = Roster::where('user_id', $user->id)
                ->whereDate('date', $yesterday)
                ->whereHas('shift', function ($q) {
                    $q->where('is_overnight', true);
                })
                ->with('shift')
                ->first();

            if ($rosterYesterday) {
                $roster = $rosterYesterday;
            }
        }
        $todaysRosterForUser = $roster;

        return view('livewire.roster-dashboard', [
            'rosters' => $rosters,
            'allUsers' => User::orderBy('name')->get(),
            'shifts' => Shift::all(),
            'shiftStats' => $shiftStats,
            'todayStats' => $todayStats,
            'todaysRosterForUser' => $todaysRosterForUser,
        ])->layout('components.layouts.app');
    }
}
