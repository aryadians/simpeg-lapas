<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

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

    #[On('roster-updated')]
    public function refreshData()
    {
        $user = Auth::user();

        // 1. Waktu Sekarang (Sesuai Timezone App)
        $now = Carbon::now();
        $today = Carbon::today();

        $this->currentTime = $now->format('H:i:s');
        $this->currentDateDisplay = $now->translatedFormat('l, d F Y');

        // 2. CEK JADWAL HARI INI
        $roster = Roster::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with('shift')
            ->first();

        // 3. LOGIKA SHIFT MALAM (CROSS DAY)
        // Jika hari ini kosong, tapi jam sekarang dini hari (00:00 - 08:00),
        // Cek apakah kemarin dia Shift Malam?
        if (!$roster && $now->hour < 8) {
            $yesterday = Carbon::yesterday();
            $rosterYesterday = Roster::where('user_id', $user->id)
                ->whereDate('date', $yesterday)
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
        $this->attendance = null; // Reset dulu
        if ($this->todayRoster) {
            $this->attendance = Attendance::where('user_id', $user->id)
                ->where('date', $this->todayRoster->date) // Gunakan tanggal roster
                ->first();
        }
    }

    public function clockIn()
    {
        if (!$this->todayRoster) {
            return;
        }

        $now = Carbon::now();
        $shift = $this->todayRoster->shift;
        $rosterDate = $this->todayRoster->date;

        $shiftStart = Carbon::parse($rosterDate . ' ' . $shift->start_time);
        $shiftEnd = Carbon::parse($rosterDate . ' ' . $shift->end_time);

        if ($shift->is_overnight) {
            $shiftEnd->addDay();
        }

        // Cek #1: Jangan biarkan absen masuk jika jam dinas sudah selesai.
        if ($now->isAfter($shiftEnd)) {
            $this->dispatch('roster-updated', message: 'Gagal: Jam dinas untuk shift ini sudah berakhir.');
            return;
        }

        // Cek #2: Jangan biarkan absen terlalu awal (misal > 1 jam sebelum mulai).
        if ($now->isBefore($shiftStart->copy()->subHour())) {
            $this->dispatch('roster-updated', message: 'Gagal: Absen masuk hanya bisa dilakukan maksimal 1 jam sebelum shift dimulai.');
            return;
        }
        
        // Penentuan Status: Hadir atau Terlambat
        $lateThreshold = $shiftStart->copy()->addMinutes(15); // Toleransi 15 menit
        $status = 'hadir'; // Default

        if ($shift->is_overnight) {
            // Untuk shift malam, status "terlambat" hanya berlaku jika user absen di hari yang sama dengan hari mulainya shift.
            if ($now->isSameDay($shiftStart) && $now->isAfter($lateThreshold)) {
                $status = 'terlambat';
            }
            // Jika user absen di hari berikutnya (setelah tengah malam), itu dianggap 'hadir' (bukan terlambat).
        } else {
            // Untuk shift biasa, jika melewati ambang batas, maka terlambat.
            if ($now->isAfter($lateThreshold)) {
                $status = 'terlambat';
            }
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $rosterDate,
            'clock_in' => $now,
            'status' => $status,
        ]);

        // Beri pesan sukses yang sesuai
        $message = 'Berhasil Absen Masuk. Status: ' . ($status == 'hadir' ? 'Tepat Waktu' : 'Terlambat');
        $this->dispatch('roster-updated', message: $message);
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
