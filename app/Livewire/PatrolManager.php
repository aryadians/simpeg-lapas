<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Checkpoint;
use App\Models\PatrolLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatrolManager extends Component
{
    public $scannedCode = '';
    public $notes = '';
    public $currentCheckpoint = null;

    public function scanCheckpoint()
    {
        $checkpoint = Checkpoint::where('location_code', $this->scannedCode)->first();

        if ($checkpoint) {
            $this->currentCheckpoint = $checkpoint;
            $this->dispatch('flash-message', text: 'Checkpoint Terdeteksi: ' . $checkpoint->name);
        } else {
            $this->dispatch('flash-message', type: 'error', title: 'Invalid!', text: 'Kode lokasi tidak dikenali.');
            $this->currentCheckpoint = null;
        }
    }

    public function submitPatrol()
    {
        if (!$this->currentCheckpoint) return;

        PatrolLog::create([
            'user_id' => Auth::id(),
            'checkpoint_id' => $this->currentCheckpoint->id,
            'notes' => $this->notes
        ]);

        $this->reset(['scannedCode', 'notes', 'currentCheckpoint']);
        $this->dispatch('flash-message', text: 'Data patroli berhasil disimpan!');
    }

    public function render()
    {
        $recentPatrols = PatrolLog::with(['user', 'checkpoint'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('livewire.patrol-manager', [
            'recentPatrols' => $recentPatrols
        ])->layout('components.layouts.app');
    }
}
