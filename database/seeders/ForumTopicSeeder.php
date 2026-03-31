<?php

namespace Database\Seeders;

use App\Models\ForumTopic;
use Illuminate\Database\Seeder;

class ForumTopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'title' => 'Rationale of the Forum',
                'slug' => 'rationale-of-the-forum',
                'category' => 'Flood Control and Mitigation',
                'summary' => 'A long-form forum story connecting PH Haiyan\'s mangrove work, youth climate education, Balugo proposals, and the urgency behind the flood-control forum.',
                'body' => "In 2017, we brought this vision to life by proposing and implementing a 46-hectare mangrove reforestation project along Tacloban's coastline, in partnership with the Department of Environment and Natural Resources (DENR). Mangroves, as we all know, are nature's first line of defense against storm surges - a lesson painfully learned during Yolanda.\n\nWe also recognize the importance of empowering the youth, so in 2017, we organized the first Regional Conference on Climate Adaptation and Mitigation for college students. This gathered 250 students from all State Universities in Region 8, creating a new generation of climate advocates.\n\nIn recent years, PH Haiyan Advocacy expanded our vision. We submitted proposals for the reforestation and development of a 364-hectare watershed above Balugo Falls in Barangay Salvacion, as well as the creation of a climate resiliency learning center that can accommodate 200 people - a hub where experts, students, and community leaders can work together on climate solutions.\n\nTHE URGENCY OF FORUM\n\nIn November 2024, we were once again reminded of how vulnerable we still are. Tropical Storm Kristine submerged entire areas in Bicol and Naga City, and it became clear that Taclobanon's flood-control systems must be reviewed - not after another disaster, but now, before the next storm comes. This is why we reached out to the Department of Public Works and Highways (DPWH) and requested a copy of Tacloban City's Flood Control Master Plan. The forum is organized in partnership with the Eastern Visayas State University (EVSU). To demonstrate our collective resolve, PH Haiyan Advocacy, in collaboration with local leaders, experts, and stakeholders, spearheaded the Flood Control Mitigation Forum. A key outcome of this forum was the signing of the Manifesto of Commitment for a Flood-Free Tacloban, a pledge that unites government agencies, academic institutions, civic organizations, and residents in a shared mission to implement sustainable flood-control solutions.",
                'image' => '/images/imported/floodcontrol/flood-control-forum.jpg',
                'starter_name' => 'PH Haiyan Advocacy',
                'status' => 'open',
                'tags' => ['flood-control', 'climate-adaptation', 'forum'],
                'replies_count' => 18,
                'views_count' => 524,
                'is_featured' => true,
                'is_pinned' => true,
                'last_activity_at' => now()->subDays(2),
            ],
            [
                'title' => 'What should a Comprehensive Flood Control Master Plan for Tacloban include?',
                'slug' => 'comprehensive-flood-control-master-plan-for-tacloban',
                'category' => 'Flood Control and Mitigation',
                'summary' => 'A discussion on planning priorities, drainage, accountability, and resilience standards for a citywide flood-control roadmap.',
                'starter_name' => 'Policy Desk',
                'status' => 'open',
                'tags' => ['flood-control', 'planning', 'city-governance'],
                'replies_count' => 14,
                'views_count' => 386,
                'is_featured' => true,
                'is_pinned' => false,
                'last_activity_at' => now()->subDays(4),
            ],
            [
                'title' => 'How can Adopt-a-Tree stay policy-compliant and safe?',
                'slug' => 'how-can-adopt-a-tree-stay-policy-compliant-and-safe',
                'category' => 'Tree Planting and Greening',
                'summary' => 'Members weigh roadside safety, engineering standards, right-of-way issues, and the environmental value of the Tacloban Bypass Road campaign.',
                'starter_name' => 'Greening Team',
                'status' => 'open',
                'tags' => ['adopt-a-tree', 'dpwh', 'roadside-greening'],
                'replies_count' => 11,
                'views_count' => 271,
                'is_featured' => true,
                'is_pinned' => false,
                'last_activity_at' => now()->subDays(6),
            ],
            [
                'title' => 'What protection measures are needed for Cancabato Bay right now?',
                'slug' => 'what-protection-measures-are-needed-for-cancabato-bay',
                'category' => 'Environmental Governance',
                'summary' => 'A forum on water quality monitoring, reclamation questions, and the legal duties tied to Cancabato Bay as a protected area.',
                'starter_name' => 'Environmental Desk',
                'status' => 'open',
                'tags' => ['cancabato-bay', 'water-quality', 'environmental-law'],
                'replies_count' => 9,
                'views_count' => 233,
                'is_featured' => false,
                'is_pinned' => false,
                'last_activity_at' => now()->subDays(9),
            ],
            [
                'title' => 'How should native tree species be prioritized in roadside greening?',
                'slug' => 'how-should-native-tree-species-be-prioritized',
                'category' => 'Tree Planting and Greening',
                'summary' => 'Participants discuss Banaba and other indigenous species suited for the Tacloban Bypass Road tree-growing effort.',
                'starter_name' => 'Tree Planting Volunteers',
                'status' => 'open',
                'tags' => ['native-trees', 'banaba', 'greening'],
                'replies_count' => 8,
                'views_count' => 205,
                'is_featured' => false,
                'is_pinned' => false,
                'last_activity_at' => now()->subDays(11),
            ],
            [
                'title' => 'How can agencies, schools, and communities coordinate resilience work better?',
                'slug' => 'how-can-agencies-schools-and-communities-coordinate-better',
                'category' => 'Community Preparedness',
                'summary' => 'A practical discussion on coordination, public trust, and keeping environmental advocacy collaborative instead of siloed.',
                'starter_name' => 'Community Partnerships',
                'status' => 'open',
                'tags' => ['coordination', 'schools', 'community-action'],
                'replies_count' => 7,
                'views_count' => 194,
                'is_featured' => false,
                'is_pinned' => false,
                'last_activity_at' => now()->subDays(13),
            ],
        ];

        foreach ($topics as $topic) {
            ForumTopic::query()->updateOrCreate(
                ['slug' => $topic['slug']],
                $topic,
            );
        }
    }
}
