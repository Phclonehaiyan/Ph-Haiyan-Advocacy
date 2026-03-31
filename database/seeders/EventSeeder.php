<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Flood Control Mitigation Forum and Manifesto for a Flood-Free Tacloban',
                'slug' => 'voices-for-resilience-flood-control-and-mitigation-forum',
                'category' => 'Flood Control Forum',
                'summary' => 'After Tropical Storm Kristine exposed how vulnerable communities remain, PH Haiyan organized a public forum with EVSU to push for urgent flood-control review before the next storm.',
                'description' => 'PH Haiyan requested a copy of Tacloban City\'s Flood Control Master Plan from DPWH and organized the forum in partnership with Eastern Visayas State University. A key outcome was the signing of the Manifesto of Commitment for a Flood-Free Tacloban, uniting agencies, academic institutions, civic groups, and residents behind sustainable flood-control solutions.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'EVSU Tacloban',
                'image' => '/images/imported/floodcontrol/flood-control-forum.jpg',
                'is_featured' => true,
                'is_published' => true,
                'start_at' => '2025-03-07 09:00:00',
                'end_at' => '2025-03-07 16:00:00',
            ],
            [
                'title' => '46-Hectare Mangrove Reforestation Along Tacloban\'s Coastline',
                'slug' => 'mangrove-reforestation-tacloban-coastline',
                'category' => 'Environmental Restoration',
                'summary' => 'In 2017, PH Haiyan proposed and implemented a 46-hectare mangrove reforestation project with DENR to strengthen Tacloban\'s coastline against storm surges.',
                'description' => 'The old website records that as of September 2018, DENR certified a 90% survival rate for the mangroves planted in the project\'s reforestation sites. It reports that 39.50 hectares were enhanced, around 50 hectares were covered across six barangays, roughly 460,000 seedlings were planted from November 2017 to September 2018, and about 5.8 kilometers of planting sites were fenced with fishnets to protect them from natural and human disturbance.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Tacloban coastline',
                'image' => '/images/imported/events/event-mangrove-reforestation.jpg',
                'is_featured' => true,
                'is_published' => true,
                'start_at' => '2017-06-15 08:30:00',
                'end_at' => '2017-06-15 16:00:00',
            ],
            [
                'title' => 'Regional Conference on Climate Adaptation and Mitigation for College Students',
                'slug' => 'regional-climate-adaptation-mitigation-conference-college-students',
                'category' => 'Youth Climate Forum',
                'summary' => 'PH Haiyan gathered 250 students from State Universities in Region 8 to help form a new generation of climate advocates.',
                'description' => 'The old website records that the second Regional Climate Change Conference was held on November 20-21, 2017 in partnership with the Climate Change Commission. It brought together senior high and college students, along with teachers and administrators from 31 state universities and colleges across Region VIII, to build stronger awareness of climate impacts and the measures communities can take to mitigate and adapt.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Regional conference venue, Eastern Visayas',
                'image' => '/images/imported/events/event-youth-climate-conference.jpg',
                'is_featured' => true,
                'is_published' => true,
                'start_at' => '2017-11-10 09:00:00',
                'end_at' => '2017-11-10 17:00:00',
            ],
            [
                'title' => 'Balugo Watershed Reforestation and Climate Resiliency Learning Center Proposal',
                'slug' => 'balugo-watershed-and-climate-resiliency-learning-center-proposal',
                'category' => 'Watershed Initiative',
                'summary' => 'PH Haiyan expanded its vision with proposals for a 364-hectare watershed above Balugo Falls and a climate resiliency learning center for 200 people.',
                'description' => 'The old website describes technical studies and preparations for a Climate Resiliency Center in Barangay Salvacion, Tacloban City. It frames the 100-hectare site, with its watershed and falls, as a place for recreation and learning, with a proposed training and activity center with 50-bed sleeping quarters and a longer-term vision for a living museum of Philippine flora and fauna aligned with a ridge-to-reef approach to climate proofing.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Balugo Falls watershed, Barangay Salvacion',
                'image' => '/images/imported/events/event-balugo-watershed.jpg',
                'is_featured' => true,
                'is_published' => true,
                'start_at' => '2024-08-12 08:00:00',
                'end_at' => '2024-08-12 11:00:00',
            ],
            [
                'title' => 'Launching Tree Planting Plan Briefing',
                'slug' => 'launching-tree-planting-plan-briefing',
                'category' => 'Tree Planting',
                'summary' => 'A planning briefing focused on greening the Tacloban Bypass Road and mobilizing public support.',
                'description' => 'The activity helped frame the Adopt-a-Tree effort as a visible public campaign for greener road corridors, safer streetscapes, and stronger climate resilience.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Tacloban Bypass Road corridor',
                'image' => '/images/imported/news/launching-tree-planting-plan.png',
                'is_featured' => false,
                'is_published' => true,
                'start_at' => '2025-05-25 08:30:00',
                'end_at' => '2025-05-25 11:30:00',
            ],
            [
                'title' => 'First Inter-Agency Coordination Meeting',
                'slug' => 'first-inter-agency-coordination-meeting',
                'category' => 'Coordination',
                'summary' => 'An inter-agency meeting convened by PH Haiyan to address issues affecting the Adopt-a-Tree Project.',
                'description' => 'The meeting focused on right-of-way questions, encroachments, and the policy issues surrounding roadside tree planting along the Tacloban Bypass Road.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Tacloban City coordination meeting venue',
                'image' => '/images/imported/gallery/first-interagency-meeting.jpg',
                'is_featured' => false,
                'is_published' => true,
                'start_at' => '2025-08-07 10:00:00',
                'end_at' => '2025-08-07 12:00:00',
            ],
            [
                'title' => 'Joint Site Inspection for the Adopt-a-Tree Project',
                'slug' => 'joint-site-inspection-for-adopt-a-tree',
                'category' => 'Field Activity',
                'summary' => 'PH Haiyan led a site inspection with key agencies along the Tacloban Bypass Road.',
                'description' => 'The inspection verified field conditions, documented right-of-way issues, and strengthened the factual basis for follow-up coordination and policy advocacy.',
                'location' => 'Tacloban City, Leyte',
                'venue' => 'Tacloban Bypass Road',
                'image' => '/images/imported/gallery/joint-site-inspection.png',
                'is_featured' => false,
                'is_published' => true,
                'start_at' => '2025-08-12 08:00:00',
                'end_at' => '2025-08-12 11:00:00',
            ],
        ];

        foreach ($events as $event) {
            Event::query()->updateOrCreate(
                ['slug' => $event['slug']],
                $event,
            );
        }
    }
}
