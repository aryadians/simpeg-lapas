<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\IncidentReport;
use App\Models\Inventory;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalEmployees;
    public $onDutyToday;
    public $pendingLeaveRequests;
    public $recentIncidents;
    public $overdueInventory;
    public $presentToday;

    public function mount()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized Access');
        }

        // Fetch data that doesn't need to be refreshed on every render
        $this->totalEmployees = User::count();
        $this->pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $this->recentIncidents = IncidentReport::where('created_at', '>=', Carbon::now()->subHours(24))->count();
        $this->overdueInventory = Inventory::where('status', 'checked_out')->whereNotNull('due_at')->where('due_at', '<', Carbon::now())->count();
        $this->presentToday = Attendance::whereDate('check_in_time', Carbon::today())->count();
        $this->onDutyToday = \App\Models\Roster::where('date', Carbon::today())->distinct('user_id')->count();
    }

    public function render()
    {
        return view('livewire.admin-dashboard')
            ->layout('components.layouts.app');
    }
}
