<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;

class TukinReport extends Component
{
    public $selectedMonth;
    public $users;
    public $reportData = [];

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->generateReport();
    }

    public function generateReport()
    {
        $this->users = User::with(['attendances' => function ($query) {
            $query->whereMonth('date', Carbon::parse($this->selectedMonth)->month)
                  ->whereYear('date', Carbon::parse($this->selectedMonth)->year);
        }, 'rosters.shift'])->get();

        $this->reportData = [];

        foreach ($this->users as $user) {
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

            $startDate = Carbon::parse($this->selectedMonth)->startOfMonth();
            $endDate = Carbon::parse($this->selectedMonth)->endOfMonth();

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateString = $date->format('Y-m-d');
                $attendance = $attendances->get($date->format('Y-m-d'));
                $roster = $rosters->get($dateString);
                $shift = $roster ? $roster->shift : null;

                $deduction = 0;
                $status = 'Tidak ada jadwal';
                $notes = '';

                if ($attendance) {
                    $status = $attendance->status;
                    if ($attendance->status === 'alpha') {
                        $deduction = 3;
                        $notes = 'Alpha';
                    } elseif ($attendance->status === 'terlambat' && $shift) {
                        $clockIn = Carbon::parse($attendance->clock_in);
                        $startTime = Carbon::parse($shift->start_time);
                        $lateInMinutes = $clockIn->diffInMinutes($startTime);

                        if ($lateInMinutes > 60) {
                            $deduction = 1.5;
                            $notes = 'Terlambat > 1 jam';
                        } elseif ($lateInMinutes > 0) {
                            $deduction = 0.5;
                            $notes = 'Terlambat < 1 jam';
                        }
                    } else {
                        $notes = 'Hadir tepat waktu';
                    }
                } elseif ($roster) {
                    // Ada jadwal tapi tidak ada record absensi = alpha
                    $deduction = 3;
                    $status = 'alpha';
                    $notes = 'Alpha (tidak ada rekam kehadiran)';
                }


                $totalDeductionPercentage += $deduction;

                $userReport['attendances'][] = [
                    'date' => $date->format('d-m-Y'),
                    'status' => $status,
                    'deduction' => $deduction,
                    'notes' => $notes,
                    'clock_in' => $attendance ? $attendance->clock_in : '-',
                    'shift_name' => $shift ? $shift->name : 'Tidak ada shift',
                    'shift_start' => $shift ? $shift->start_time : '-'
                ];
            }

            $totalDeductionAmount = ($totalDeductionPercentage / 100) * $user->tukin_nominal;
            $finalTukin = $user->tukin_nominal - $totalDeductionAmount;

            $userReport['total_deduction_percentage'] = $totalDeductionPercentage;
            $userReport['total_deduction_amount'] = $totalDeductionAmount;
            $userReport['final_tukin'] = $finalTukin;
            
            $this->reportData[] = $userReport;
        }
    }

    public function exportCsv()
    {
        $fileName = 'laporan-tukin-' . $this->selectedMonth . '.csv';

        // Ensure the report data is generated if it's somehow empty
        if (empty($this->reportData)) {
            $this->generateReport();
        }

        $data = $this->reportData;

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 compatibility in Excel
            fputs($file, "\xEF\xBB\xBF");

            // Add Header Row
            fputcsv($file, [
                'NIP', 
                'Nama Pegawai', 
                'Jabatan', 
                'Grade', 
                'Tukin Awal (Rp)', 
                'Total Potongan (%)', 
                'Potongan (Rp)', 
                'Tukin Diterima (Rp)'
            ]);

            // Add Data Rows
            foreach ($data as $userReport) {
                fputcsv($file, [
                    "'" . $userReport['nip'], // Prepend with ' to treat as text in Excel
                    $userReport['name'],
                    $userReport['jabatan'],
                    $userReport['grade'],
                    $userReport['tukin_nominal'],
                    $userReport['total_deduction_percentage'],
                    $userReport['total_deduction_amount'],
                    $userReport['final_tukin'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.tukin-report')->layout('components.layouts.app');
    }
}
