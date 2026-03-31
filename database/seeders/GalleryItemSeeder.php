<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Joint Site Inspection',
                'slug' => 'joint-site-inspection-gallery',
                'category' => 'Events',
                'summary' => 'PH Haiyan led the first NGO-led site inspection with key agencies along the Tacloban Bypass Road.',
                'image' => '/images/imported/gallery/joint-site-inspection.png',
                'is_featured' => true,
                'sort_order' => 1,
                'taken_at' => '2025-08-12 09:00:00',
            ],
            [
                'title' => 'First Inter-Agency Coordination Meeting',
                'slug' => 'first-inter-agency-coordination-meeting-gallery',
                'category' => 'Events',
                'summary' => 'An inter-agency coordination meeting initiated by PH Haiyan Advocacy Inc.',
                'image' => '/images/imported/gallery/first-interagency-meeting.jpg',
                'is_featured' => true,
                'sort_order' => 2,
                'taken_at' => '2025-08-07 10:00:00',
            ],
            [
                'title' => 'Flood Control and Mitigation',
                'slug' => 'flood-control-and-mitigation-gallery',
                'category' => 'Forums',
                'summary' => 'Scenes from the Flood Control and Mitigation Forum in EVSU Tacloban.',
                'image' => '/images/imported/floodcontrol/flood-control-forum.jpg',
                'is_featured' => false,
                'sort_order' => 3,
                'taken_at' => '2025-06-01 09:00:00',
            ],
            [
                'title' => 'Launching Tree Planting Plan',
                'slug' => 'launching-tree-planting-plan-gallery',
                'category' => 'Tree Planting',
                'summary' => "A public-facing call to help make Tacloban's roads greener.",
                'image' => '/images/imported/news/launching-tree-planting-plan.png',
                'is_featured' => false,
                'sort_order' => 4,
                'taken_at' => '2025-05-25 09:00:00',
            ],
            [
                'title' => 'Adopt-a-Tree',
                'slug' => 'adopt-a-tree-gallery',
                'category' => 'Tree Planting',
                'summary' => 'Campaign material promoting the bypass-road tree-growing project with DPWH and DENR partners.',
                'image' => '/images/imported/news/adopt-a-tree.png',
                'is_featured' => false,
                'sort_order' => 5,
                'taken_at' => '2025-07-04 09:00:00',
            ],
            [
                'title' => 'Tree Guard Preparation',
                'slug' => 'tree-guard-preparation-gallery',
                'category' => 'Campaigns',
                'summary' => 'Custom tree guards prepared to protect and support newly planted trees along the bypass road.',
                'image' => '/images/imported/news/tree-guard-installation.png',
                'is_featured' => false,
                'sort_order' => 6,
                'taken_at' => '2025-07-08 09:00:00',
            ],
            [
                'title' => "What's Really Happening in Cancabato Bay?",
                'slug' => 'whats-really-happening-in-cancabato-bay',
                'category' => 'Campaigns',
                'summary' => "A public-facing image used in PH Haiyan's environmental questions and bay-protection messaging.",
                'image' => '/images/imported/news/flooding-threat.png',
                'is_featured' => false,
                'sort_order' => 7,
                'taken_at' => '2025-04-30 09:00:00',
            ],
            [
                'title' => 'Building Resilience',
                'slug' => 'building-resilience-gallery',
                'category' => 'Community Activities',
                'summary' => "Highlights from PH Haiyan's public resilience work and flood-control advocacy activities.",
                'image' => '/images/imported/floodcontrol/building-resilience.jpg',
                'is_featured' => false,
                'sort_order' => 8,
                'taken_at' => '2025-04-22 09:00:00',
            ],
        ];

        foreach ($items as $item) {
            GalleryItem::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
