<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SystemSettings extends Component
{
    public $office_latitude;
    public $office_longitude;
    public $geofence_radius;
    public $allowed_ips;
    public $app_name;

    public function mount()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $this->app_name = Setting::where('key', 'app_name')->first()?->value ?? config('app.name');
        $this->office_latitude = Setting::where('key', 'office_latitude')->first()?->value ?? config('app.office_latitude');
        $this->office_longitude = Setting::where('key', 'office_longitude')->first()?->value ?? config('app.office_longitude');
        $this->geofence_radius = Setting::where('key', 'geofence_radius')->first()?->value ?? 100;
        $this->allowed_ips = Setting::where('key', 'allowed_ips')->first()?->value ?? '';
    }

    public function save()
    {
        $this->validate([
            'office_latitude' => 'required|numeric',
            'office_longitude' => 'required|numeric',
            'geofence_radius' => 'required|integer|min:10',
            'app_name' => 'required|string',
        ]);

        $settings = [
            'app_name' => $this->app_name,
            'office_latitude' => $this->office_latitude,
            'office_longitude' => $this->office_longitude,
            'geofence_radius' => $this->geofence_radius,
            'allowed_ips' => $this->allowed_ips,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->dispatch('flash-message', text: 'Konfigurasi sistem berhasil diperbarui secara global.');
    }

    public function render()
    {
        return view('livewire.system-settings')->layout('components.layouts.app');
    }
}
