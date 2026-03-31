<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Joint Site Inspection',
                'slug' => 'joint-site-inspection',
                'category' => 'Coordination',
                'summary' => 'PH Haiyan led the first NGO-led site inspection with key agencies along the Tacloban Bypass Road.',
                'content' => [
                    'highlights' => [
                        'The team documented field conditions and right-of-way issues affecting the Adopt-a-Tree campaign.',
                        'The inspection helped turn public concern into evidence-based follow-up with agencies.',
                    ],
                ],
                'location' => 'Tacloban Bypass Road, Tacloban City',
                'image' => '/images/imported/gallery/joint-site-inspection.png',
                'is_featured' => true,
                'activity_date' => '2025-08-12 08:00:00',
            ],
            [
                'title' => 'First Inter-Agency Coordination Meeting',
                'slug' => 'first-inter-agency-coordination-meeting-activity',
                'category' => 'Forum',
                'summary' => 'Inter-agency coordination convened by PH Haiyan to address roadside planting and policy questions.',
                'content' => [
                    'highlights' => [
                        'The meeting centered on right-of-way acquisition, encroachment, and coordination barriers.',
                        'It laid groundwork for later site inspections, letters, and policy-facing advocacy.',
                    ],
                ],
                'location' => 'Tacloban City, Leyte',
                'image' => '/images/imported/gallery/first-interagency-meeting.jpg',
                'is_featured' => true,
                'activity_date' => '2025-08-07 10:00:00',
            ],
            [
                'title' => 'Morning Tree Guard Installation with DPWH',
                'slug' => 'morning-tree-guard-installation-with-dpwh-activity',
                'category' => 'Tree Planting',
                'summary' => 'Tree guards were installed along the bypass road to protect Banaba trees and reinforce long-term maintenance.',
                'content' => [
                    'highlights' => [
                        'The activity showed that roadside greening requires protection and follow-through, not just ceremonial planting.',
                        'PH Haiyan worked with DPWH to make the effort more visible and durable.',
                    ],
                ],
                'location' => 'Tacloban Bypass Road, Tacloban City',
                'image' => '/images/imported/news/tree-guard-installation.png',
                'is_featured' => true,
                'activity_date' => '2025-07-08 08:00:00',
            ],
            [
                'title' => 'Launching Tree Planting Plan',
                'slug' => 'launching-tree-planting-plan',
                'category' => 'Tree Planting',
                'summary' => 'A public-facing launch effort that framed roadside greening as a climate-resilience priority for Tacloban.',
                'content' => [
                    'highlights' => [
                        "The campaign encouraged people to help make Tacloban's roads greener.",
                        'It also helped build momentum for the Adopt-a-Tree initiative and related public messaging.',
                    ],
                ],
                'location' => 'Tacloban City, Leyte',
                'image' => '/images/imported/news/launching-tree-planting-plan.png',
                'is_featured' => false,
                'activity_date' => '2025-05-25 09:00:00',
            ],
            [
                'title' => 'Building Resilience',
                'slug' => 'building-resilience',
                'category' => 'Flood Control',
                'summary' => 'Highlights from the March 7, 2025 Flood Control and Mitigation Forum in EVSU Tacloban.',
                'content' => [
                    'highlights' => [
                        'The forum surfaced urgent concerns around flood planning and local preparedness.',
                        'PH Haiyan used the event to keep resilience planning connected to public dialogue and practical city action.',
                    ],
                ],
                'location' => 'EVSU Tacloban, Tacloban City',
                'image' => '/images/imported/floodcontrol/building-resilience.jpg',
                'is_featured' => false,
                'activity_date' => '2025-04-22 09:00:00',
            ],
        ];

        foreach ($activities as $activity) {
            Activity::query()->updateOrCreate(
                ['slug' => $activity['slug']],
                $activity,
            );
        }
    }
}
