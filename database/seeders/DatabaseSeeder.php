<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shift;
use App\Models\Roster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. Buat Master Shift ---

        $shiftPagi = Shift::create([
            'name' => 'Regu Pagi',
            'start_time' => '07:00:00',
            'end_time' => '13:00:00',
            'is_overnight' => false,
        ]);

        $shiftSiang = Shift::create([
            'name' => 'Regu Siang',
            'start_time' => '13:00:00',
            'end_time' => '19:00:00',
            'is_overnight' => false,
        ]);

        $shiftMalam = Shift::create([
            'name' => 'Regu Malam',
            'start_time' => '19:00:00',
            'end_time' => '07:00:00',
            'is_overnight' => true, // Logic penting untuk shift lintas hari
        ]);

        $shiftKantor = Shift::create([
            'name' => 'Jam Kantor',
            'start_time' => '07:30:00',
            'end_time' => '16:00:00',
            'is_overnight' => false,
        ]);

        // --- 2. Buat Akun Admin (Untuk Kamu Login) ---
        User::create([
            'name' => 'Arya (Super Admin)',
            'email' => 'arya@lapas.com',
            'password' => Hash::make('password'), // Password standar
            'nip' => '199901012025011001',
            'jabatan' => 'Pranata Komputer',
            'grade' => 9,
        ]);

        // --- 3. Buat 10 Petugas Rupam Dumy ---
        // Kita pakai Factory bawaan Laravel untuk nama acak
        $petugas = User::factory(10)->create([
            'jabatan' => 'Anggota Jaga',
            'grade' => 5,
        ]);

        // --- 4. Buat Jadwal Acak (7 Hari ke Depan) ---
        $listShiftRupam = [$shiftPagi, $shiftSiang, $shiftMalam];

        foreach ($petugas as $user) {
            for ($i = 0; $i < 7; $i++) {
                // Pilih shift secara acak
                $randomShift = $listShiftRupam[array_rand($listShiftRupam)];

                Roster::create([
                    'user_id' => $user->id,
                    'shift_id' => $randomShift->id,
                    'date' => now()->addDays($i)->format('Y-m-d'),
                ]);
            }
        }
    }
}
