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
                'title' => 'WiFi Gratuit à Bord',
                'content' => 'Profitez d\'une connexion WiFi gratuite et illimitée dans tous nos bus. Restez connecté tout au long de votre voyage !',
                'is_active' => true,
                'position' => 'header',
                'audio_file' => 'wifi_announcement.mp3'
            ],
            [
                'title' => 'Service Client Premium',
                'content' => 'Notre équipe de service client est disponible 24h/24 et 7j/7 pour répondre à toutes vos questions. Contactez-nous au 0800 123 456.',
                'is_active' => true,
                'position' => 'footer',
                'audio_file' => 'customer_service.mp3'
            ],
            [
                'title' => 'Système de Divertissement',
                'content' => 'Découvrez notre nouveau système de divertissement à bord avec films récents, musique et jeux. Voyagez en vous amusant !',
                'is_active' => true,
                'position' => 'header',
                'audio_file' => 'entertainment.mp3'
            ],
            [
                'title' => 'Programme de Fidélité',
                'content' => 'Rejoignez notre programme de fidélité et gagnez des points à chaque voyage. Profitez de réductions exclusives et d\'avantages spéciaux.',
                'is_active' => true,
                'position' => 'sidebar',
                'audio_file' => 'loyalty_program.mp3'
            ],
            [
                'title' => 'Mesures Sanitaires',
                'content' => 'Votre sécurité est notre priorité. Nos bus sont désinfectés après chaque voyage et équipés de purificateurs d\'air.',
                'is_active' => true,
                'position' => 'popup',
                'audio_file' => 'safety_measures.mp3'
            ],
            [
                'title' => 'Nouvelle Application Mobile',
                'content' => 'Téléchargez notre nouvelle application mobile pour réserver vos billets, suivre votre bus en temps réel et accéder à votre carte de fidélité.',
                'is_active' => true,
                'position' => 'header',
                'audio_file' => 'mobile_app.mp3'
            ]
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
