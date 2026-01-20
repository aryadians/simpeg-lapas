<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceReport extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        // Default bulan & tahun sekarang
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function render()
    {
        // Ambil semua pegawai
        $users = User::orderBy('name', 'asc')->get();

        // Siapkan array untuk menampung data rekap
        $report = [];

        foreach ($users as $user) {
            // Ambil data absen user ini di bulan & tahun yang dipilih
            $attendances = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();

            $report[$user->id] = [
                'name' => $user->name,
                'nip' => $user->nip,
                'hadir' => $attendances->where('status', 'hadir')->count(),
                'terlambat' => $attendances->where('status', 'terlambat')->count(),
                'alpha' => $attendances->where('status', 'alpha')->count(),
                'total_kehadiran' => $attendances->count()
            ];
        }

        return view('livewire.attendance-report', [
            'report' => $report
        ])->layout('components.layouts.app');
    }
}
