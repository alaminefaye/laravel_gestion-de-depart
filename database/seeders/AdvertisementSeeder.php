<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $advertisements = [
            [
                'title' => 'Découvrez nos nouveaux bus de luxe',
                'video_type' => 'youtube',
                'video_link' => 'https://www.youtube.com/watch?v=example1',
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promotion spéciale weekend',
                'video_type' => 'youtube',
                'video_link' => 'https://www.youtube.com/watch?v=example2',
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Services VIP disponibles',
                'video_type' => 'youtube',
                'video_link' => 'https://www.youtube.com/watch?v=example3',
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Offre spéciale groupes',
                'video_type' => 'youtube',
                'video_link' => 'https://www.youtube.com/watch?v=example4',
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Destinations de rêve',
                'video_type' => 'youtube',
                'video_link' => 'https://www.youtube.com/watch?v=example5',
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('advertisements')->insert($advertisements);
    }
}
