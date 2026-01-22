<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class AttendanceWidget extends Component
{
    use WithFileUploads;

    public $todayRoster;
    public $attendance;
    public $currentTime;
    public $currentDateDisplay;

    // Geofencing & Selfie Properties
    public $userLatitude;
    public $userLongitude;
    public $isWithinRadius = false;
    public $locationError = null;
    public $distance = null;
    public $showSelfieModal = false;
    public $selfie;
    
    protected $rules = [
        'selfie' => 'required|image|max:5120', // 5MB Max
    ];

    public function mount($todayRoster = null)
    {
        $this->todayRoster = $todayRoster;
        $this->refreshAttendanceData();
        $this->currentTime = Carbon::now()->format('H:i:s');
        $this->currentDateDisplay = Carbon::now()->translatedFormat('l, d F Y');
    }

    public function refreshAttendanceData()
    {
        $this->attendance = null;
        if ($this->todayRoster) {
            $this->attendance = Attendance::where('user_id', Auth::id())
                ->where('date', $this->todayRoster->date)
                ->first();
        }
    }

    public function setUserLocation($latitude, $longitude)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;

        $officeLat = config('app.office_latitude');
        $officeLon = config('app.office_longitude');
        
        $this->distance = $this->calculateDistance($latitude, $longitude, $officeLat, $officeLon);

        if ($this->distance <= 100) { // 100 meter radius
            $this->isWithinRadius = true;
            $this->locationError = null;
        } else {
            $this->isWithinRadius = false;
            $this->locationError = 'Anda berada ' . round($this->distance) . ' meter dari lokasi. Anda harus berada dalam radius 100 meter untuk absen.';
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000; // meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }

    public function clockIn()
    {
        if (!$this->isWithinRadius) {
            $this->dispatch('flash-message', type: 'error', title: 'Lokasi Tidak Valid', text: $this->locationError ?? 'Gagal memverifikasi lokasi Anda.');
            return;
        }
        
        // Additional business logic checks before showing selfie modal
        $now = Carbon::now();
        $shift = $this->todayRoster->shift;
        $rosterDate = $this->todayRoster->date;
        $shiftStart = Carbon::parse($rosterDate . ' ' . $shift->start_time);
        $shiftEnd = Carbon::parse($rosterDate . ' ' . $shift->end_time);

        if ($shift->is_overnight) {
            $shiftEnd->addDay();
        }
        
        if ($now->isAfter($shiftEnd)) {
            $this->dispatch('flash-message', type: 'error', title: 'Gagal', text: 'Jam dinas untuk shift ini sudah berakhir.');
            return;
        }

        if ($now->isBefore($shiftStart->copy()->subHours(2))) { // Increased to 2 hours
            $this->dispatch('flash-message', type: 'error', title: 'Gagal', text: 'Absen masuk hanya bisa dilakukan maksimal 2 jam sebelum shift dimulai.');
            return;
        }

        $this->showSelfieModal = true;
    }
    
    public function cancelClockIn()
    {
        $this->showSelfieModal = false;
        $this->reset('selfie');
    }

    public function confirmClockIn()
    {
        if (!$this->isWithinRadius) {
            $this->dispatch('flash-message', type: 'error', title: 'Lokasi Tidak Valid', text: 'Sesi lokasi Anda telah berakhir. Mohon refresh halaman.');
            $this->cancelClockIn();
            return;
        }

        $this->validate();

        $now = Carbon::now();
        $rosterDate = $this->todayRoster->date;
        $shiftStart = Carbon::parse($rosterDate . ' ' . $this->todayRoster->shift->start_time);

        $status = 'hadir';
        if ($now->isAfter($shiftStart)) {
            $status = 'terlambat';
        }
        
        $selfiePath = $this->selfie->store('selfies', 'public');

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $rosterDate,
            'clock_in' => $now,
            'status' => $status,
            'latitude_check_in' => $this->userLatitude,
            'longitude_check_in' => $this->userLongitude,
            'selfie_check_in' => $selfiePath,
        ]);

        $message = 'Berhasil Absen Masuk. Status: ' . ($status == 'hadir' ? 'Tepat Waktu' : 'Terlambat');
        $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: $message);
        
        $this->cancelClockIn();
        $this->refreshAttendanceData();
        $this->dispatch('attendance-changed');
    }

    public function clockOut()
    {
        // Note: Geofencing and selfie for clock-out is not implemented per user request.
        // This would be the place to add it.
        if ($this->attendance) {
            $this->attendance->update([
                'clock_out' => Carbon::now()
            ]);

            $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: 'Berhasil Absen Pulang!');
            $this->refreshAttendanceData();
            $this->dispatch('attendance-changed');
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}