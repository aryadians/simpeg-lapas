<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DailyLog;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\On;

class Logbook extends Component
{
    public $wbp_count, $description, $is_urgent = false;
    public $shift_name;
    public $showForm = false;

    public function mount()
    {
        $hour = Carbon::now()->hour;
        if ($hour >= 7 && $hour < 13) $this->shift_name = 'Regu Pagi';
        elseif ($hour >= 13 && $hour < 19) $this->shift_name = 'Regu Siang';
        else $this->shift_name = 'Regu Malam';
    }

    public function showCreateForm()
    {
        $this->showForm = true;
    }

    public function cancel()
    {
        $this->reset(['wbp_count', 'description', 'is_urgent']);
        $this->showForm = false;
    }

    public function submitLog()
    {
        $this->validate([
            'wbp_count' => 'required|numeric|min:0',
            'description' => 'required|string|min:10',
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
        $this->showForm = false;
        $this->dispatch('flash-message', text: 'Laporan aplusan berhasil dikirim!');
    }

    public function deleteLog($id)
    {
        // Pengecekan keamanan: hanya admin atau pemilik log yang bisa hapus
        $log = DailyLog::findOrFail($id);
        if (auth()->user()->role !== 'admin' && auth()->id() !== $log->user_id) {
            $this->dispatch('flash-message', type: 'error', title: 'Akses Ditolak!', text: 'Anda tidak memiliki izin untuk menghapus laporan ini.');
            return;
        }

        $this->dispatch('confirm-dialog', title: 'Hapus Laporan?', text: 'Anda yakin ingin menghapus laporan ini secara permanen?', confirm_event: 'deleteLogConfirmed', confirm_params: $id);
    }

    #[On('deleteLogConfirmed')]
    public function deleteLogConfirmed($id)
    {
        DailyLog::find($id)->delete();
        $this->dispatch('flash-message', type: 'info', text: 'Laporan telah dihapus.');
    }

    public function render()
    {
        $logs = DailyLog::with('user')->latest()->take(30)->get();

        return view('livewire.logbook', [
            'logs' => $logs
        ])->layout('components.layouts.app');
    }
}
