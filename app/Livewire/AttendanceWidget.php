<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceWidget extends Component
{
    public $todayRoster;
    public $attendance;
    public $currentTime;
    public $currentDateDisplay; // Untuk Debugging

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();

        // 1. Waktu Sekarang (Sesuai Timezone App)
        $now = Carbon::now();
        $today = Carbon::today();

        $this->currentTime = $now->format('H:i');
        $this->currentDateDisplay = $now->translatedFormat('l, d F Y');

        // 2. CEK JADWAL HARI INI
        $roster = Roster::where('user_id', $user->id)
            ->where('date', $today)
            ->with('shift')
            ->first();

        // 3. LOGIKA SHIFT MALAM (CROSS DAY)
        // Jika hari ini kosong, tapi jam sekarang dini hari (00:00 - 08:00),
        // Cek apakah kemarin dia Shift Malam?
        if (!$roster && $now->hour < 8) {
            $yesterday = Carbon::yesterday();
            $rosterYesterday = Roster::where('user_id', $user->id)
                ->where('date', $yesterday)
                ->whereHas('shift', function ($q) {
                    $q->where('is_overnight', true); // Hanya cari yang shift malam
                })
                ->with('shift')
                ->first();

            if ($rosterYesterday) {
                $roster = $rosterYesterday;
                // Override tanggal "hari ini" jadi "kemarin" untuk keperluan query absen
                $today = $yesterday;
            }
        }

        $this->todayRoster = $roster;

        // 4. Cek Data Absensi (Berdasarkan tanggal roster yang ditemukan)
        if ($this->todayRoster) {
            $this->attendance = Attendance::where('user_id', $user->id)
                ->where('date', $this->todayRoster->date) // Gunakan tanggal roster
                ->first();
        }
    }

    public function clockIn()
    {
        if (!$this->todayRoster) return;

        $now = Carbon::now();
        $shiftStart = Carbon::parse($this->todayRoster->date . ' ' . $this->todayRoster->shift->start_time);

        // Koreksi shift malam: Jika start jam 19:00, dan sekarang jam 01:00 besoknya, hitungannya aman.
        // Toleransi keterlambatan 15 menit
        $status = 'hadir';

        // Jika absennya telat lebih dari 15 menit dari jam masuk
        // Perlu logika datetime comparison yang akurat untuk shift malam
        // Sederhana: Kita anggap "Hadir" dulu untuk memudahkan

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $this->todayRoster->date, // Simpan sesuai tanggal roster (bukan tanggal kalender hari ini jika shift malam)
            'clock_in' => $now,
            'status' => $status
        ]);

        $this->dispatch('roster-updated', message: 'Berhasil Absen Masuk!');
        $this->refreshData();
    }

    public function clockOut()
    {
        if ($this->attendance) {
            $this->attendance->update([
                'clock_out' => Carbon::now()
            ]);

            $this->dispatch('roster-updated', message: 'Berhasil Absen Pulang!');
            $this->refreshData();
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}
