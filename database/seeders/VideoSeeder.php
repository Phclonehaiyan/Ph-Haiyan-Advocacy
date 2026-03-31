<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        Video::query()->delete();

        $videos = [
            [
                'title' => '8 Buwan Nang Walang Desisyon? Ano na ang Nangyayari sa Tubig ng Tacloban?',
                'slug' => 'facebook-water-delay',
                'summary' => 'A public-interest video calling attention to prolonged inaction on Tacloban\'s water issues and the need for accountable decisions.',
                'thumbnail' => '/images/imported/videos/facebook-water-delay.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/688800710959565/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '3.3K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 18:00:00',
            ],
            [
                'title' => 'Kun Ospital May Problema ha Tubig, Kita Pa Ba nga Ordinaryo nga Consumers?',
                'slug' => 'facebook-hospital-water',
                'summary' => 'A pointed video on water service accountability, asking what ordinary consumers can expect if hospitals are already affected.',
                'thumbnail' => '/images/imported/videos/facebook-hospital-water.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/1463475732147808/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '66K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 17:00:00',
            ],
            [
                'title' => 'Sige la it Panukot, Bis Waray Tubig. Hain an 24-Oras nga Patapod?',
                'slug' => 'facebook-billing-service',
                'summary' => 'A short accountability reel on billing, service gaps, and the demand for dependable 24-hour water access.',
                'thumbnail' => '/images/imported/videos/facebook-billing-service.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/3692124607594575/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '3.8K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 16:00:00',
            ],
            [
                'title' => 'May-ada ba gud kita 24-oras nga supply hin tubig?',
                'slug' => 'facebook-24-hour-supply',
                'summary' => 'A direct public question on whether Tacloban residents are truly receiving the consistent 24-hour water supply they are promised.',
                'thumbnail' => '/images/imported/videos/facebook-24-hour-supply.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/2044413719465929/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '6.8K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 15:00:00',
            ],
            [
                'title' => 'LMWD–PrimeWater: May Serbisyo, May Pananagutan — Natutuman Ba?',
                'slug' => 'facebook-lmwd-primewater',
                'summary' => 'A service-accountability video asking whether water commitments are being met and how public responsibility should be measured.',
                'thumbnail' => '/images/imported/videos/facebook-lmwd-primewater.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/1584403589450985/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '5K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 14:00:00',
            ],
            [
                'title' => 'Hindi Pa Ganito Kaganda ang Tacloban — Pero Ganito ang Kaya Nito',
                'slug' => 'facebook-tacloban-vision',
                'summary' => 'A more hopeful city-focused reel showing Tacloban\'s potential and the kind of future resilience work can help shape.',
                'thumbnail' => '/images/imported/videos/facebook-tacloban-vision.jpg',
                'video_url' => 'https://www.facebook.com/phhaiyanadvocacy/videos/2158930124847045/',
                'platform' => 'Facebook Reel',
                'view_count_label' => '56K views',
                'duration' => null,
                'is_featured' => true,
                'published_at' => '2026-03-24 13:00:00',
            ],
        ];

        foreach ($videos as $video) {
            Video::query()->create($video);
        }
    }
}
