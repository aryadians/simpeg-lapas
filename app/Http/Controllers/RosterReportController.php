<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RosterReportController extends Controller
{
    public function print()
    {
        // 1. Ambil data bulan ini
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // 2. Ambil jadwal urut berdasarkan Nama Pegawai, lalu Tanggal
        // Kita join dengan tabel users agar bisa urut abjad nama
        $rosters = Roster::join('users', 'rosters.user_id', '=', 'users.id')
            ->select('rosters.*') // Ambil kolom roster saja
            ->with(['user', 'shift'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('users.name') // Urutkan nama A-Z
            ->orderBy('rosters.date') // Lalu urutkan tanggal
            ->get()
            ->groupBy('user_id'); // Kelompokkan per orang

        // 3. Generate PDF
        $pdf = Pdf::loadView('pdf.roster-schedule', [
            'rosters' => $rosters,
            'monthName' => $startDate->locale('id')->isoFormat('MMMM Y'),
            'dates' => $this->getDatesInMonth($startDate)
        ]);

        // 4. Download file
        return $pdf->setPaper('a4', 'landscape')->stream('Jadwal-Dinas.pdf');
    }

    // Helper untuk membuat array tanggal 1-30/31
    private function getDatesInMonth($date)
    {
        $dates = [];
        $daysInMonth = $date->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = $i;
        }

        return $dates;
    }
}
