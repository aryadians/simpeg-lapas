<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceCalendar extends Component
{
    public $month;
    public $year;
    public $daysInMonth = [];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->generateCalendar();
    }

    public function generateCalendar()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1);
        $daysInMonthCount = $date->daysInMonth;
        
        $attendances = Attendance::where('user_id', Auth::id())
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get()
            ->keyBy('date');

        $this->daysInMonth = [];

        // Padding awal (agar hari pertama sesuai dengan nama hari)
        $firstDayOfWeek = $date->dayOfWeek; // 0 (Sun) to 6 (Sat)
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $this->daysInMonth[] = null;
        }

        for ($day = 1; $day <= $daysInMonthCount; $day++) {
            $currentDate = Carbon::createFromDate($this->year, $this->month, $day)->format('Y-m-d');
            $this->daysInMonth[] = [
                'day' => $day,
                'attendance' => $attendances->get($currentDate)
            ];
        }
    }

    public function prevMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->generateCalendar();
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->generateCalendar();
    }

    public function render()
    {
        return view('livewire.attendance-calendar')->layout('components.layouts.app');
    }
}
