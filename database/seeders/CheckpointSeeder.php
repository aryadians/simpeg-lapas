<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Checkpoint;

class CheckpointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checkpoints = [
            [
                'name' => 'Pintu Gerbang Utama (P2U)',
                'location_code' => 'P2U-MAIN-GATE',
                'description' => 'Area pemeriksaan utama masuk dan keluar Lapas.'
            ],
            [
                'name' => 'Blok Hunian A (Narkotika)',
                'location_code' => 'BLOK-A-NARKO',
                'description' => 'Patroli lorong dan selasar Blok A.'
            ],
            [
                'name' => 'Blok Hunian B (Umum)',
                'location_code' => 'BLOK-B-UMUM',
                'description' => 'Patroli lorong dan selasar Blok B.'
            ],
            [
                'name' => 'Menara Jaga 1 (Utara)',
                'location_code' => 'TOWER-01-NORTH',
                'description' => 'Pemeriksaan perimeter menara sudut utara.'
            ],
            [
                'name' => 'Menara Jaga 2 (Selatan)',
                'location_code' => 'TOWER-02-SOUTH',
                'description' => 'Pemeriksaan perimeter menara sudut selatan.'
            ],
            [
                'name' => 'Area Dapur & Logistik',
                'location_code' => 'KITCHEN-AREA',
                'description' => 'Pemeriksaan kebersihan dan keamanan gudang makanan.'
            ],
            [
                'name' => 'Poliklinik',
                'location_code' => 'CLINIC-ZONE',
                'description' => 'Area pelayanan kesehatan warga binaan.'
            ],
        ];

        foreach ($checkpoints as $cp) {
            Checkpoint::updateOrCreate(
                ['location_code' => $cp['location_code']],
                $cp
            );
        }
    }
}
