<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import Library PDF

class RosterReportController extends Controller
{
    public function print()
    {
        // 1. Ambil Bulan Ini
        $currentDate = Carbon::now();
        $monthName = $currentDate->locale('id')->monthName;
        $year = $currentDate->year;

        // 2. Ambil Semua Pegawai (Diurutkan berdasarkan pangkat/nama)
        $users = User::orderBy('grade', 'desc')->orderBy('name', 'asc')->get();

        // 3. Siapkan Array Tanggal (1 s/d 30/31)
        $daysInMonth = $currentDate->daysInMonth;
        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = Carbon::createFromDate($year, $currentDate->month, $i);
        }

        // 4. Ambil Data Jadwal (Roster) Bulan Ini
        $rosters = Roster::with('shift')
            ->whereMonth('date', $currentDate->month)
            ->whereYear('date', $year)
            ->get();

        // 5. Mapping Data: [user_id][tanggal] = 'P' (Kode Shift)
        $schedule = [];
        foreach ($rosters as $roster) {
            // Ambil huruf pertama/kode dari nama shift. 
            // Misal: "Regu Pagi" -> ambil huruf ke-5 "P". 
            // Atau sesuaikan logika ini dengan nama shift di database kamu.
            // Cara aman: Ambil huruf pertama dari kata kedua.
            $parts = explode(' ', $roster->shift->name);
            $initial = isset($parts[1]) ? substr($parts[1], 0, 1) : substr($roster->shift->name, 0, 1);

            $schedule[$roster->user_id][$roster->date] = $initial;
        }

        // 6. Generate PDF dari View
        $pdf = Pdf::loadView('pdf.roster-report', [
            'users' => $users,
            'dates' => $dates,
            'schedule' => $schedule,
            'monthName' => $monthName,
            'year' => $year
        ]);

        // Set Kertas Legal (F4) Landscape
        $pdf->setPaper('legal', 'landscape');

        return $pdf->stream('Jadwal-Dinas-' . $monthName . '-' . $year . '.pdf');
    }
}
