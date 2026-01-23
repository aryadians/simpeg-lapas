<?php

use Illuminate\Support\Facades\Route;

// Import semua Controller & Livewire Component
use App\Livewire\Auth\Login;
use App\Livewire\RosterDashboard;
use App\Livewire\EmployeeManager;
use App\Livewire\LeaveManager;
use App\Livewire\Logbook;
use App\Livewire\UserProfile;
use App\Livewire\TukinReport;
use App\Http\Controllers\RosterReportController;
use App\Livewire\IncidentManager;
use App\Livewire\InventoryManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Rute Tamu (Guest) - Hanya bisa diakses jika BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// 2. Rute Terproteksi (Auth) - Hanya bisa diakses jika SUDAH login
Route::middleware('auth')->group(function () {

    // Dashboard Utama (Jadwal & Absen)
    Route::get('/', RosterDashboard::class)->name('dashboard');

    // Manajemen Pegawai
    Route::get('/pegawai', EmployeeManager::class)->name('pegawai');

    // Manajemen Inventaris
    Route::get('/inventaris', InventoryManager::class)->name('inventaris');

    // Laporan Kejadian Internal
    Route::get('/laporan-kejadian', IncidentManager::class)->name('incident-reports');

    // E-Cuti (Manajemen Cuti)
    Route::get('/cuti', LeaveManager::class)->name('cuti');

    // Laporan Aplusan (Logbook)
    Route::get('/laporan', Logbook::class)->name('laporan');

    // Laporan Tukin
    Route::get('/laporan-tukin', TukinReport::class)->name('tukin.report');

    // Plotting Regu & Pos Jaga
    Route::get('/post-assignment', \App\Livewire\PostAssignment::class)->name('post.assignment');

    // Profil User (Ganti Password)
    Route::get('/profil', UserProfile::class)->name('profil');

    // Cetak PDF
    Route::get('/cetak-laporan', [RosterReportController::class, 'print'])->name('cetak');
    Route::get('/cetak-laporan-tukin/{month}', [RosterReportController::class, 'printTukinReport'])->name('tukin.report.pdf');
    Route::get('/rekap-absensi', \App\Livewire\AttendanceReport::class)->name('rekap');

    // Logout
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
