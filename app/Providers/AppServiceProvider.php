<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Overwrite config with database settings if table exists
        if (Schema::hasTable('settings')) {
            $settings = Setting::all();
            
            foreach ($settings as $setting) {
                if ($setting->key === 'app_name') {
                    config(['app.name' => $setting->value]);
                } elseif ($setting->key === 'office_latitude') {
                    config(['app.office_latitude' => $setting->value]);
                } elseif ($setting->key === 'office_longitude') {
                    config(['app.office_longitude' => $setting->value]);
                } elseif ($setting->key === 'allowed_ips') {
                    config(['app.allowed_ips' => $setting->value]);
                }
            }
        }
    }
}
