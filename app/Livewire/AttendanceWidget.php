<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class AttendanceWidget extends Component
{
    use WithFileUploads;

    public $todayRoster;
    public $attendance;
    public $currentTime;
    public $currentDateDisplay;

    public $userLatitude;
    public $userLongitude;
    public $isWithinRadius = false;
    public $locationError = null;
    public $distance = null;
    public $showSelfieModal = false;
    public $selfie;
    
    protected $rules = [
        'selfie' => 'required',
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
        $this->isWithinRadius = true; // Bypass for testing
        $this->locationError = null;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000;
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function clockIn()
    {
        $this->showSelfieModal = true;
    }
    
    public function cancelClockIn()
    {
        $this->showSelfieModal = false;
        $this->reset('selfie');
    }

    public function confirmClockIn()
    {
        $this->validate();

        $now = Carbon::now();
        $rosterDate = $this->todayRoster->date;
        $shiftStart = Carbon::parse($rosterDate . ' ' . $this->todayRoster->shift->start_time);

        $status = $now->isAfter($shiftStart) ? 'terlambat' : 'hadir';
        
        $selfiePath = '';
        if (is_string($this->selfie) && strpos($this->selfie, 'data:image') === 0) {
            $image = str_replace('data:image/jpeg;base64,', '', $this->selfie);
            $image = str_replace(' ', '+', $image);
            $imageName = 'selfies/' . Str::random(40) . '.jpg';
            Storage::disk('public')->put($imageName, base64_decode($image));
            $selfiePath = $imageName;
        } elseif ($this->selfie instanceof \Illuminate\Http\UploadedFile) {
            $selfiePath = $this->selfie->store('selfies', 'public');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $rosterDate,
            'clock_in' => $now->toTimeString(),
            'status' => $status,
            'latitude_check_in' => $this->userLatitude,
            'longitude_check_in' => $this->userLongitude,
            'selfie_check_in' => $selfiePath,
        ]);

        $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: 'Absen Masuk Berhasil.');
        $this->cancelClockIn();
        $this->refreshAttendanceData();
        $this->dispatch('attendance-changed');
    }

    #[On('clock-out-trigger')]
    public function clockOut($data = null)
    {
        // Re-fetch attendance to ensure it's fresh
        $record = Attendance::where('user_id', Auth::id())
            ->where('date', $this->todayRoster->date)
            ->first();

        if ($record) {
            $record->update([
                'clock_out' => Carbon::now()->toTimeString()
            ]);

            $this->dispatch('flash-message', type: 'success', title: 'Berhasil', text: 'Absen Pulang Berhasil.');
            $this->refreshAttendanceData();
            $this->dispatch('attendance-changed');
        } else {
            $this->dispatch('flash-message', type: 'error', title: 'Gagal', text: 'Data absensi tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.attendance-widget');
    }
}
