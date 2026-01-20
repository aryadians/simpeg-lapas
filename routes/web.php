<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RosterDashboard;
use App\Livewire\EmployeeManager;
use App\Http\Controllers\RosterReportController;
use App\Livewire\Auth\Login; // Import Login

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', RosterDashboard::class);
Route::get('/pegawai', EmployeeManager::class);
Route::get('/cetak-laporan', [RosterReportController::class, 'print']);
// 1. Rute Login (Bisa diakses siapa saja/Guest)
Route::get('/login', Login::class)->name('login')->middleware('guest');

// 2. Rute Logout (Penting)
Route::get('/logout', function () {
    auth()->logout();
    session()->invalidate();
    return redirect('/login');
});

// 3. Rute Terproteksi (Hanya user Login)
// Kita bungkus semua halaman penting dalam grup 'auth'
Route::middleware('auth')->group(function () {

    Route::get('/', RosterDashboard::class);
    Route::get('/pegawai', EmployeeManager::class);
    Route::get('/cetak-laporan', [RosterReportController::class, 'print']);
});
