<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DailyLog;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Logbook extends Component
{
    public $wbp_count, $description, $is_urgent = false;
    public $shift_name;

    public function mount()
    {
        // Otomatis deteksi shift berdasarkan jam sekarang (Sederhana)
        $hour = Carbon::now()->hour;
        if ($hour >= 7 && $hour < 13) $this->shift_name = 'Regu Pagi';
        elseif ($hour >= 13 && $hour < 19) $this->shift_name = 'Regu Siang';
        else $this->shift_name = 'Regu Malam';
    }

    public function submitLog()
    {
        $this->validate([
            'wbp_count' => 'required|numeric',
            'description' => 'required|min:10',
        ]);

        DailyLog::create([
            'user_id' => Auth::id(),
            'date' => Carbon::today(),
            'shift_name' => $this->shift_name,
            'wbp_count' => $this->wbp_count,
            'description' => $this->description,
            'is_urgent' => $this->is_urgent
        ]);

        $this->reset(['wbp_count', 'description', 'is_urgent']);
        $this->dispatch('roster-updated', message: 'Laporan Aplusan berhasil dikirim!');
    }

    public function deleteLog($id)
    {
        DailyLog::find($id)->delete();
    }

    public function render()
    {
        // Admin lihat semua, Staff lihat 5 hari terakhir
        $logs = DailyLog::with('user')->latest()->take(20)->get();

        return view('livewire.logbook', [
            'logs' => $logs
        ])->layout('components.layouts.app');
    }
}
