<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class PanicHandler extends Component
{
    public $activeAlert = null;

    #[On('trigger-panic')]
    public function handlePanic($data = null)
    {
        $user = Auth::user();
        
        // Simpan log Panic
        AuditLog::create([
            'user_id' => $user->id,
            'event' => 'panic_alert',
            'auditable_type' => 'Emergency',
            'auditable_id' => $user->id,
            'new_values' => json_encode(['location' => 'Manual Trigger', 'message' => 'Panic button pressed by ' . $user->name]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Broadcast alert (Simulasi via local property)
        $this->activeAlert = [
            'user' => $user->name,
            'time' => now()->format('H:i:s')
        ];

        $this->dispatch('flash-message', type: 'error', title: 'EMERGENCY ALERT!', text: 'Panic Button diaktifkan oleh ' . $user->name);
    }

    public function dismissAlert()
    {
        $this->activeAlert = null;
    }

    public function render()
    {
        return view('livewire.panic-handler');
    }
}
