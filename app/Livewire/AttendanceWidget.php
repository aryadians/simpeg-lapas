<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceWidget extends Component
{
    public $todayRoster;
    public $attendance;
    public $currentTime;

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Cek Jadwal Hari Ini
        $this->todayRoster = Roster::where('user_id', $user->id)
            ->where('date', $today)
            ->with('shift')
            ->first();

        // 2. Cek Data Absensi Hari Ini
        $this->attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $this->currentTime = Carbon::now()->format('H:i');
    }

    public function clockIn()
    {
        // Validasi: Harus punya jadwal dulu
        if (!$this->todayRoster) {
            $this->dispatch('roster-updated', message: 'Anda tidak memiliki jadwal dinas hari ini!');
            return;
        }

        $now = Carbon::now();
        $shiftStart = Carbon::parse($this->todayRoster->shift->start_time);

        // Logika Terlambat (Toleransi 15 menit)
        $status = $now->greaterThan($shiftStart->addMinutes(15)) ? 'terlambat' : 'hadir';

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => Carbon::today(),
            'clock_in' => $now,
            'status' => $status
        ]);

        $this->dispatch('roster-updated', message: 'Berhasil Absen Masuk!');
        $this->refreshData();
    }

    public function clockOut()
    {
        if ($this->attendance) {
            $this->attendance->update([
                'clock_out' => Carbon::now()
            ]);

            $this->dispatch('roster-updated', message: 'Berhasil Absen Pulang. Hati-hati di jalan!');
            $this->refreshData();
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}
