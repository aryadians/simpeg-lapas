<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import Library PDF

use App\Models\PatrolLog;

class RosterReportController extends Controller
{
    public function print()
    {
        // ... (keep existing print method)
    }

    public function printTukinReport($month)
    {
        // ... (keep existing printTukinReport method)
    }

    public function printPatrolReport($month)
    {
        $selectedMonth = Carbon::parse($month);
        
        $patrolLogs = PatrolLog::with(['user', 'checkpoint'])
            ->whereMonth('created_at', $selectedMonth->month)
            ->whereYear('created_at', $selectedMonth->year)
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.patrol-report', [
            'patrolLogs' => $patrolLogs,
            'monthName' => $selectedMonth->locale('id')->monthName,
            'year' => $selectedMonth->year
        ]);

        return $pdf->stream('Laporan-Patroli-' . $selectedMonth->format('F-Y') . '.pdf');
    }
}
}

