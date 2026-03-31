<?php

return [
    'organization' => [
        'name' => 'PH Haiyan Advocacy Inc.',
        'short_name' => 'PH Haiyan Advocacy',
        'tagline' => 'Climate resilience, environmental stewardship, and citizen participation for Tacloban and Eastern Visayas.',
        'registration_blurb' => 'A non-profit, non-government advocacy organization established in the aftermath of Typhoon Haiyan (Yolanda) in 2013.',
    ],

    'contact' => [
        'email' => 'phhaiyanadvocacy6500@gmail.com',
        'phone' => '+63 954 179 3532',
        'location' => 'Tacloban City, Leyte, Philippines',
        'address' => 'No. 1 Beta Bayview Homes Subdivision, Barangay 88, San Jose, Tacloban City, Leyte, Philippines',
        'hours' => 'Monday to Friday, 8:00 AM to 5:00 PM',
        'map_embed' => 'https://maps.google.com/maps?q=Tacloban%20City,%20Leyte,%20Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed',
    ],

    'socials' => [
        [
            'label' => 'Facebook',
            'handle' => '@phhaiyanadvocacy',
            'href' => 'https://www.facebook.com/phhaiyanadvocacy',
        ],
        [
            'label' => 'YouTube',
            'handle' => '@phhaiyan',
            'href' => 'https://www.youtube.com/@phhaiyan',
        ],
        [
            'label' => 'Instagram',
            'handle' => '@Phhaiyan',
            'href' => 'https://instagram.com/Phhaiyan',
        ],
    ],

    'navigation' => [
        [
            'label' => 'Home',
            'route' => 'home',
        ],
        [
            'label' => 'About',
            'children' => [
                [
                    'label' => 'About',
                    'route' => 'about',
                ],
                [
                    'label' => 'What We Do',
                    'route' => 'what-we-do',
                ],
            ],
        ],
        [
            'label' => 'Gallery',
            'children' => [
                [
                    'label' => 'Gallery',
                    'route' => 'gallery.index',
                ],
                [
                    'label' => 'Forums',
                    'route' => 'forums.index',
                ],
                [
                    'label' => 'Projects',
                    'route' => 'projects.index',
                ],
            ],
        ],
        [
            'label' => 'Letters',
            'route' => 'letters.index',
        ],
        [
            'label' => 'Contact',
            'route' => 'contact.index',
        ],
    ],

    'quick_links' => [
        [
            'label' => 'Home',
            'route' => 'home',
        ],
        [
            'label' => 'About PH Haiyan',
            'route' => 'about',
        ],
        [
            'label' => 'What We Do',
            'route' => 'what-we-do',
        ],
        [
            'label' => 'Projects',
            'route' => 'projects.index',
        ],
        [
            'label' => 'Contact',
            'route' => 'contact.index',
        ],
    ],

    'support' => [
        'headline' => 'Support practical climate-resilience work in Tacloban and Eastern Visayas.',
        'summary' => 'Your support helps PH Haiyan sustain tree-growing initiatives, flood-control advocacy, public forums, watershed and bay protection efforts, and community-based climate education.',
        'actions' => [
            [
                'label' => 'Donate',
                'description' => 'Help sustain tree-growing drives, public education, and issue-based advocacy work.',
                'href' => 'contact.index',
                'inquiry' => 'donate-support',
                'icon' => 'heart',
            ],
            [
                'label' => 'Volunteer',
                'description' => 'Join field activities, forums, and community-facing climate resilience campaigns.',
                'href' => 'contact.index',
                'inquiry' => 'volunteer',
                'icon' => 'users',
            ],
            [
                'label' => 'Partner With Us',
                'description' => 'Work with PH Haiyan on environmental programs, forums, and public-interest initiatives.',
                'href' => 'contact.index',
                'inquiry' => 'partnership',
                'icon' => 'handshake',
            ],
        ],
    ],

    'inquiry_types' => [
        'volunteer' => 'Volunteer',
        'donate-support' => 'Donate / Support',
        'partnership' => 'Partnership',
        'general-inquiry' => 'General Inquiry',
    ],

    'footer' => [
        'summary' => 'Founded in the aftermath of Typhoon Haiyan, PH Haiyan Advocacy Inc. works with communities, public institutions, and partner organizations to advance environmental protection, climate resilience, and accountable local action.',
        'trust_line' => 'Built for climate resilience, environmental stewardship, and public trust.',
        'donate_note' => 'Help us plant more trees and sustain community-based advocacy.',
    ],

    'seo' => [
        'default_title' => 'PH Haiyan Advocacy Inc. | Climate Resilience, Environmental Protection, and Community Empowerment',
        'default_description' => 'PH Haiyan Advocacy Inc. is an advocacy organization in the Philippines focused on climate resilience, environmental protection, disaster preparedness, mangrove reforestation, and community empowerment.',
        'default_keywords' => [
            'PH Haiyan Advocacy Inc.',
            'environmental protection Philippines',
            'climate resilience Philippines',
            'disaster preparedness Philippines',
            'mangrove reforestation Philippines',
            'community empowerment Philippines',
            'advocacy organization Philippines',
            'Yolanda advocacy',
            'Haiyan advocacy',
            'environmental awareness Philippines',
        ],
        'default_og_image' => '/images/brand/ph-haiyan-logo.png',
        'organization_logo' => '/images/brand/ph-haiyan-logo.png',
        'default_robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
        'twitter_card' => 'summary_large_image',
        'twitter_site' => '@phhaiyanadvocacy',
        'organization_type' => 'NGO',
        'area_served' => 'Philippines',
        'founding_date' => '2013',
        'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
        'ga_measurement_id' => env('GA_MEASUREMENT_ID'),
        'route_defaults' => [
            'home' => [
                'title' => 'PH Haiyan Advocacy Inc. | Environmental and Climate Resilience Advocacy in the Philippines',
                'description' => 'Discover PH Haiyan Advocacy Inc., an advocacy organization in the Philippines promoting environmental protection, climate resilience, disaster preparedness, and community empowerment.',
            ],
            'about' => [
                'title' => 'About PH Haiyan Advocacy Inc. | Our Mission for Resilience and Environmental Advocacy',
                'description' => 'Learn about PH Haiyan Advocacy Inc., our mission, vision, and commitment to environmental protection, resilience, and community empowerment in the Philippines.',
            ],
            'what-we-do' => [
                'title' => 'What We Do | Climate Resilience, Environmental Programs, and Community Action',
                'description' => 'Explore the programs of PH Haiyan Advocacy Inc., including climate resilience initiatives, environmental awareness, mangrove efforts, and disaster preparedness advocacy.',
            ],
            'gallery.index' => [
                'title' => 'Gallery | PH Haiyan Advocacy Inc. Activities and Community Engagement',
                'description' => 'View photos and highlights from PH Haiyan Advocacy Inc.’s activities, environmental programs, and community resilience efforts in the Philippines.',
            ],
            'forums.index' => [
                'title' => 'Forums | Community Discussions on Environment and Resilience',
                'description' => 'Join discussions and shared insights related to environmental protection, resilience, advocacy, and community development.',
            ],
            'letters.index' => [
                'title' => 'Letters and Statements | PH Haiyan Advocacy Inc.',
                'description' => 'Read official letters, statements, and advocacy messages from PH Haiyan Advocacy Inc.',
            ],
            'contact.index' => [
                'title' => 'Contact PH Haiyan Advocacy Inc. | Get in Touch',
                'description' => 'Contact PH Haiyan Advocacy Inc. for partnerships, inquiries, collaborations, and advocacy-related concerns.',
            ],
            'news.index' => [
                'title' => 'News and Updates | PH Haiyan Advocacy Inc.',
                'description' => 'Read environmental advocacy updates, climate resilience stories, and public-interest news from PH Haiyan Advocacy Inc.',
            ],
            'projects.index' => [
                'title' => 'Projects | PH Haiyan Advocacy Inc. Climate and Environmental Programs',
                'description' => 'Explore PH Haiyan Advocacy Inc. project records covering climate resilience, environmental protection, mangrove work, and community-focused advocacy in the Philippines.',
            ],
            'events.index' => [
                'title' => 'Events and Archive Highlights | PH Haiyan Advocacy Inc.',
                'description' => 'Browse PH Haiyan Advocacy Inc. events, forums, and archive highlights related to climate resilience, environmental protection, and public advocacy.',
            ],
            'support' => [
                'title' => 'Support the Mission | PH Haiyan Advocacy Inc.',
                'description' => 'Support PH Haiyan Advocacy Inc. through partnerships, donations, and volunteer opportunities that strengthen climate resilience and environmental action in the Philippines.',
            ],
            'search.index' => [
                'title' => 'Site Search | PH Haiyan Advocacy Inc.',
                'description' => 'Search across PH Haiyan Advocacy Inc. pages, letters, records, gallery items, and archive content.',
                'robots' => 'noindex,follow',
            ],
        ],
    ],
];
