<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            ['name' => 'P2U (Gerbang Utama)', 'code' => 'P2U'],
            ['name' => 'Komandan Jaga', 'code' => 'KJD'],
            ['name' => 'Pos Menara Atas', 'code' => 'PMA'],
            ['name' => 'Blok Tahanan', 'code' => 'BTK'],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
