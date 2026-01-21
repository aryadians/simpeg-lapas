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
    public $currentDateDisplay;

    public function mount($todayRoster = null)
    {
        $this->todayRoster = $todayRoster;
        $this->refreshAttendanceData();
        $this->currentTime = Carbon::now()->format('H:i:s');
        $this->currentDateDisplay = Carbon::now()->translatedFormat('l, d F Y');
    }

    public function refreshAttendanceData()
    {
        $this->attendance = null;
        if ($this->todayRoster) {
            $this->attendance = Attendance::where('user_id', Auth::id())
                ->where('date', $this->todayRoster->date)
                ->first();
        }
    }

    public function clockIn()
    {
        if (!$this->todayRoster) {
            return;
        }

        $now = Carbon::now();
        $shift = $this->todayRoster->shift;
        $rosterDate = $this->todayRoster->date;

        $shiftStart = Carbon::parse($rosterDate . ' ' . $shift->start_time);
        $shiftStart = Carbon::parse($rosterDate . ' ' . $shift->start_time);

        if ($shift->is_overnight) {
            $shiftEnd->addDay();
        }

        if ($now->isAfter($shiftEnd)) {
            $this->dispatch('flash-message', type: 'error', title: 'Gagal', text: 'Jam dinas untuk shift ini sudah berakhir.');
            return;
        }

        if ($now->isBefore($shiftStart->copy()->subHour())) {
            $this->dispatch('flash-message', type: 'error', title: 'Gagal', text: 'Absen masuk hanya bisa dilakukan maksimal 1 jam sebelum shift dimulai.');
            return;
        }
        
        $status = 'hadir';
        if ($now->isAfter($shiftStart)) {
            $status = 'terlambat';
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $rosterDate,
            'clock_in' => $now,
            'status' => $status,
        ]);

        $message = 'Berhasil Absen Masuk. Status: ' . ($status == 'hadir' ? 'Tepat Waktu' : 'Terlambat');
        $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: $message);
        $this->refreshAttendanceData();
        $this->dispatch('attendance-changed');
    }

    public function clockOut()
    {
        if ($this->attendance) {
            $this->attendance->update([
                'clock_out' => Carbon::now()
            ]);

            $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: 'Berhasil Absen Pulang!');
            $this->refreshAttendanceData();
            $this->dispatch('attendance-changed');
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}