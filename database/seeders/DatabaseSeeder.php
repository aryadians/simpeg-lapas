<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift; // Import Model Shift
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Shift Dulu (Wajib ada biar jadwal jalan)
        Shift::insert([
            ['name' => 'Regu Pagi', 'start_time' => '07:00:00', 'end_time' => '13:00:00', 'is_overnight' => false],
            ['name' => 'Regu Siang', 'start_time' => '13:00:00', 'end_time' => '19:00:00', 'is_overnight' => false],
            ['name' => 'Regu Malam', 'start_time' => '19:00:00', 'end_time' => '07:00:00', 'is_overnight' => true],
        ]);

        // 2. Buat 1 Akun Admin (Untuk Login Kamu)
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@lapas.com',
            'password' => bcrypt('password'), // Password gampang
            'jabatan' => 'Kalapas',
            'nip' => '111122223333',
            'grade' => 15
        ]);

        // 3. Buat 50 Pegawai Biasa (Dummy)
        User::factory(50)->create();
    }
}
