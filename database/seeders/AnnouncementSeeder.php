<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'WiFi Gratuit',
                'content' => 'Pour votre confort, nos bus sont désormais équipés de WiFi gratuit',
                'is_active' => true,
                'position' => 'header'
            ],
            [
                'title' => 'Service Client 24/7',
                'content' => 'Notre service client est disponible 24h/24 et 7j/7 pour vous assister',
                'is_active' => true,
                'position' => 'header'
            ],
            [
                'title' => 'Nouveau système de divertissement',
                'content' => 'Profitez de notre nouveau système de divertissement à bord avec films et musique',
                'is_active' => true,
                'position' => 'header'
            ]
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
