<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. BUAT DATA SHIFT (Menggunakan updateOrCreate agar aman)
        // ---------------------------------------------------
        // Kita kunci ID-nya 1, 2, 3 agar sesuai dengan logika Auto Generate

        $shifts = [
            [
                'id' => 1,
                'name' => 'Regu Pagi',
                'start_time' => '07:00:00',
                'end_time' => '13:00:00',
                'is_overnight' => false
            ],
            [
                'id' => 2,
                'name' => 'Regu Siang',
                'start_time' => '13:00:00',
                'end_time' => '19:00:00',
                'is_overnight' => false
            ],
            [
                'id' => 3,
                'name' => 'Regu Malam',
                'start_time' => '19:00:00',
                'end_time' => '07:00:00',
                'is_overnight' => true
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(['id' => $shift['id']], $shift);
        }

        // ---------------------------------------------------
        // 2. BUAT AKUN ADMIN (Solusi Error Unique NIP)
        // ---------------------------------------------------
        // Sistem akan mengecek: "Apakah email admin@lapas.com sudah ada?"
        // Jika ADA: Update datanya (Jadi tidak error duplikat).
        // Jika TIDAK: Buat baru.

        User::updateOrCreate(
            ['email' => 'admin@lapas.com'], // Kunci pengecekan
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Password default: password
                'role' => 'admin',
                'jabatan' => 'Kalapas',
                'nip' => '111122223333',
                'grade' => 15,
                // 'tukin_nominal' => 5000000, // Uncomment jika sudah ada kolom ini
            ]
        );

        $this->call(PostSeeder::class);

        // ---------------------------------------------------
        // 3. BUAT 50 PEGAWAI DUMMY
        // ---------------------------------------------------
        // Kita cek dulu, kalau user masih sedikit baru buat dummy.
        // Supaya kalau di-seed ulang, database tidak penuh sampah.

        if (User::count() < 10) {
            User::factory(50)->create();
        }
    }
}
