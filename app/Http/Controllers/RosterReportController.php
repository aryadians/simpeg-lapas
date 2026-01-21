<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roster;
use App\Models\User;
use App\Models\Attendance;
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

    public function printTukinReport($month)
    {
        $selectedMonth = Carbon::parse($month);

        $users = User::with(['attendances' => function ($query) use ($selectedMonth) {
            $query->whereMonth('date', $selectedMonth->month)
                  ->whereYear('date', $selectedMonth->year);
        }, 'rosters.shift'])->get();

        $reportData = [];

        foreach ($users as $user) {
            $totalDeductionPercentage = 0;
            $userReport = [
                'name' => $user->name,
                'nip' => $user->nip,
                'jabatan' => $user->jabatan,
                'grade' => $user->grade,
                'tukin_nominal' => $user->tukin_nominal,
                'attendances' => [],
                'total_deduction_percentage' => 0,
                'total_deduction_amount' => 0,
                'final_tukin' => $user->tukin_nominal,
            ];

            $attendances = $user->attendances->keyBy('date');
            $rosters = $user->rosters->keyBy(function($roster) {
                return Carbon::parse($roster->date)->format('Y-m-d');
            });

            $startDate = $selectedMonth->copy()->startOfMonth();
            $endDate = $selectedMonth->copy()->endOfMonth();

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateString = $date->format('Y-m-d');
                $attendance = $attendances->get($date->format('Y-m-d'));
                $roster = $rosters->get($dateString);
                $shift = $roster ? $roster->shift : null;

                $deduction = 0;
                
                if ($attendance) {
                    if ($attendance->status === 'alpha') {
                        $deduction = 3;
                    } elseif ($attendance->status === 'terlambat' && $shift) {
                        $clockIn = Carbon::parse($attendance->clock_in);
                        $startTime = Carbon::parse($shift->start_time);
                        $lateInMinutes = $clockIn->diffInMinutes($startTime);

                        if ($lateInMinutes > 60) {
                            $deduction = 1.5;
                        } elseif ($lateInMinutes > 0) {
                            $deduction = 0.5;
                        }
                    }
                } elseif ($roster) {
                    $deduction = 3;
                }

                $totalDeductionPercentage += $deduction;
            }

            $totalDeductionAmount = ($totalDeductionPercentage / 100) * $user->tukin_nominal;
            $finalTukin = $user->tukin_nominal - $totalDeductionAmount;

            $userReport['total_deduction_percentage'] = $totalDeductionPercentage;
            $userReport['total_deduction_amount'] = $totalDeductionAmount;
            $userReport['final_tukin'] = $finalTukin;
            
            $reportData[] = $userReport;
        }

        $pdf = Pdf::loadView('pdf.tukin-report', [
            'reportData' => $reportData,
            'month' => $selectedMonth->locale('id')->isoFormat('MMMM YYYY')
        ]);

        return $pdf->stream('Laporan-Tukin-' . $selectedMonth->format('F-Y') . '.pdf');
    }
}

