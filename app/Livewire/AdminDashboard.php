<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\IncidentReport;
use App\Models\Inventory;
use App\Models\AuditLog;
use App\Models\PatrolLog;
use App\Models\ShiftExchange;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Roster;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalEmployees;
    public $onDutyToday;
    public $pendingLeaveRequests;
    public $recentIncidents;
    public $overdueInventory;
    public $presentToday;
    
    // New Stats
    public $patrolsToday;
    public $pendingSwaps;
    public $recentAuditLogs;
    
    // Chart Data
    public $attendanceTrendLabels = [];
    public $attendanceTrendData = [];
    public $lateTrendData = [];

    // Leaderboard
    public $topPerformers = [];

    public function mount()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized Access');
        }

        $this->totalEmployees = User::count();
        $this->pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $this->recentIncidents = IncidentReport::where('created_at', '>=', Carbon::now()->subHours(24))->count();
        $this->overdueInventory = Inventory::where('status', 'checked_out')->whereNotNull('due_at')->where('due_at', '<', Carbon::now())->count();
        $this->presentToday = Attendance::whereDate('date', Carbon::today())->count();
        $this->onDutyToday = Roster::whereDate('date', Carbon::today())->distinct('user_id')->count();
        
        $this->patrolsToday = PatrolLog::whereDate('created_at', Carbon::today())->count();
        $this->pendingSwaps = ShiftExchange::where('status', 'approved_by_target')->count();
        $this->recentAuditLogs = AuditLog::with('user')->latest()->take(5)->get();

        // Calculate Leaderboard (This Month)
        $this->calculateLeaderboard();

        // Prepare Chart Data (Last 7 Days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $this->attendanceTrendLabels[] = $date->format('d M');
            
            $this->attendanceTrendData[] = Attendance::whereDate('date', $date)
                ->where('status', 'hadir')
                ->count();
                
            $this->lateTrendData[] = Attendance::whereDate('date', $date)
                ->where('status', 'terlambat')
                ->count();
        }
    }

    private function calculateLeaderboard()
    {
        $users = User::where('role', 'staff')->get();
        $leaderboard = [];

        foreach($users as $user) {
            $score = 0;
            $attendances = Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->get();
            
            foreach($attendances as $att) {
                if($att->status === 'hadir') $score += 10;
                elseif($att->status === 'terlambat') $score -= 5;
                elseif($att->status === 'alpha') $score -= 20;
            }

            $score += PatrolLog::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->count() * 5;
            $score += DailyLog::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->count() * 5;

            $leaderboard[] = [
                'name' => $user->name,
                'score' => $score,
                'avatar' => strtoupper(substr($user->name, 0, 2))
            ];
        }

        usort($leaderboard, fn($a, $b) => $b['score'] <=> $a['score']);
        $this->topPerformers = array_slice($leaderboard, 0, 5);
    }

    public function render()
    {
        return view('livewire.admin-dashboard')
            ->layout('components.layouts.app');
    }
}
