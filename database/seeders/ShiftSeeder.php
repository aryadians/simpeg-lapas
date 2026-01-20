<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ID-nya urut 1, 2, 3 agar cocok dengan logika Auto Generate

        // ID 1: Pagi
        Shift::updateOrCreate(['id' => 1], [
            'name' => 'Regu Pagi',
            'start_time' => '07:00:00',
            'end_time' => '13:00:00',
            'is_overnight' => false
        ]);

        // ID 2: Siang
        Shift::updateOrCreate(['id' => 2], [
            'name' => 'Regu Siang',
            'start_time' => '13:00:00',
            'end_time' => '19:00:00',
            'is_overnight' => false
        ]);

        // ID 3: Malam
        Shift::updateOrCreate(['id' => 3], [
            'name' => 'Regu Malam',
            'start_time' => '19:00:00',
            'end_time' => '07:00:00',
            'is_overnight' => true // Lewat tengah malam
        ]);
    }
}
