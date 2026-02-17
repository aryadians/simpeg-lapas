<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogViewer extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $logs = AuditLog::with('user')
            ->where(function($q) {
                $q->where('event', 'like', '%' . $this->search . '%')
                  ->orWhere('auditable_type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($query) {
                      $query->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->latest()
            ->paginate(20);

        return view('livewire.audit-log-viewer', [
            'logs' => $logs
        ])->layout('components.layouts.app');
    }
}
