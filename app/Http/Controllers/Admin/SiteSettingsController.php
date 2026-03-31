<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteSettingsController extends Controller
{
    public function edit(): View
    {
        $site = config('site');
        $socials = data_get($site, 'socials', []);
        $navigation = data_get($site, 'navigation', []);
        $quickLinks = array_values(data_get($site, 'quick_links', []));
        $supportActions = array_values(data_get($site, 'support.actions', []));
        $inquiryTypes = data_get($site, 'inquiry_types', []);

        return view('admin.settings.edit', [
            'values' => [
                'organization_name' => data_get($site, 'organization.name'),
                'organization_short_name' => data_get($site, 'organization.short_name'),
                'organization_tagline' => data_get($site, 'organization.tagline'),
                'organization_registration_blurb' => data_get($site, 'organization.registration_blurb'),

                'contact_email' => data_get($site, 'contact.email'),
                'contact_phone' => data_get($site, 'contact.phone'),
                'contact_location' => data_get($site, 'contact.location'),
                'contact_address' => data_get($site, 'contact.address'),
                'contact_hours' => data_get($site, 'contact.hours'),
                'contact_map_embed' => data_get($site, 'contact.map_embed'),

                'support_headline' => data_get($site, 'support.headline'),
                'support_summary' => data_get($site, 'support.summary'),
                'footer_summary' => data_get($site, 'footer.summary'),
                'footer_trust_line' => data_get($site, 'footer.trust_line'),
                'footer_donate_note' => data_get($site, 'footer.donate_note'),
                'seo_default_title' => data_get($site, 'seo.default_title'),
                'seo_default_description' => data_get($site, 'seo.default_description'),
                'seo_default_keywords' => collect(data_get($site, 'seo.default_keywords', []))->implode(', '),
                'seo_default_og_image' => data_get($site, 'seo.default_og_image'),
                'seo_google_site_verification' => data_get($site, 'seo.google_site_verification'),
                'seo_ga_measurement_id' => data_get($site, 'seo.ga_measurement_id'),

                'social_facebook_handle' => data_get($socials, '0.handle'),
                'social_facebook_href' => data_get($socials, '0.href'),
                'social_youtube_handle' => data_get($socials, '1.handle'),
                'social_youtube_href' => data_get($socials, '1.href'),
                'social_instagram_handle' => data_get($socials, '2.handle'),
                'social_instagram_href' => data_get($socials, '2.href'),

                'nav_home_label' => data_get($navigation, '0.label', 'Home'),
                'nav_home_route' => data_get($navigation, '0.route', 'home'),
                'nav_about_group_label' => data_get($navigation, '1.label', 'About'),
                'nav_about_item_1_label' => data_get($navigation, '1.children.0.label', 'About'),
                'nav_about_item_1_route' => data_get($navigation, '1.children.0.route', 'about'),
                'nav_about_item_2_label' => data_get($navigation, '1.children.1.label', 'What We Do'),
                'nav_about_item_2_route' => data_get($navigation, '1.children.1.route', 'what-we-do'),
                'nav_gallery_group_label' => data_get($navigation, '2.label', 'Gallery'),
                'nav_gallery_item_1_label' => data_get($navigation, '2.children.0.label', 'Gallery'),
                'nav_gallery_item_1_route' => data_get($navigation, '2.children.0.route', 'gallery.index'),
                'nav_gallery_item_2_label' => data_get($navigation, '2.children.1.label', 'Forums'),
                'nav_gallery_item_2_route' => data_get($navigation, '2.children.1.route', 'forums.index'),
                'nav_gallery_item_3_label' => data_get($navigation, '2.children.2.label', 'Projects'),
                'nav_gallery_item_3_route' => data_get($navigation, '2.children.2.route', 'projects.index'),
                'nav_letters_label' => data_get($navigation, '3.label', 'Letters'),
                'nav_letters_route' => data_get($navigation, '3.route', 'letters.index'),
                'nav_contact_label' => data_get($navigation, '4.label', 'Contact'),
                'nav_contact_route' => data_get($navigation, '4.route', 'contact.index'),

                'quick_link_1_label' => data_get($quickLinks, '0.label', 'Home'),
                'quick_link_1_route' => data_get($quickLinks, '0.route', 'home'),
                'quick_link_2_label' => data_get($quickLinks, '1.label', 'About PH Haiyan'),
                'quick_link_2_route' => data_get($quickLinks, '1.route', 'about'),
                'quick_link_3_label' => data_get($quickLinks, '2.label', 'What We Do'),
                'quick_link_3_route' => data_get($quickLinks, '2.route', 'what-we-do'),
                'quick_link_4_label' => data_get($quickLinks, '3.label', 'Projects'),
                'quick_link_4_route' => data_get($quickLinks, '3.route', 'projects.index'),
                'quick_link_5_label' => data_get($quickLinks, '4.label', 'Contact'),
                'quick_link_5_route' => data_get($quickLinks, '4.route', 'contact.index'),

                'support_action_1_label' => data_get($supportActions, '0.label', 'Donate'),
                'support_action_1_description' => data_get($supportActions, '0.description'),
                'support_action_1_route' => data_get($supportActions, '0.href', 'contact.index'),
                'support_action_1_inquiry' => data_get($supportActions, '0.inquiry', 'donate-support'),
                'support_action_1_icon' => data_get($supportActions, '0.icon', 'heart'),
                'support_action_2_label' => data_get($supportActions, '1.label', 'Volunteer'),
                'support_action_2_description' => data_get($supportActions, '1.description'),
                'support_action_2_route' => data_get($supportActions, '1.href', 'contact.index'),
                'support_action_2_inquiry' => data_get($supportActions, '1.inquiry', 'volunteer'),
                'support_action_2_icon' => data_get($supportActions, '1.icon', 'users'),
                'support_action_3_label' => data_get($supportActions, '2.label', 'Partner With Us'),
                'support_action_3_description' => data_get($supportActions, '2.description'),
                'support_action_3_route' => data_get($supportActions, '2.href', 'contact.index'),
                'support_action_3_inquiry' => data_get($supportActions, '2.inquiry', 'partnership'),
                'support_action_3_icon' => data_get($supportActions, '2.icon', 'handshake'),

                'inquiry_label_volunteer' => data_get($inquiryTypes, 'volunteer', 'Volunteer'),
                'inquiry_label_donate_support' => data_get($inquiryTypes, 'donate-support', 'Donate / Support'),
                'inquiry_label_partnership' => data_get($inquiryTypes, 'partnership', 'Partnership'),
                'inquiry_label_general_inquiry' => data_get($inquiryTypes, 'general-inquiry', 'General Inquiry'),
            ],
            'routeOptions' => $this->routeOptions(),
            'iconOptions' => $this->iconOptions(),
            'inquiryOptions' => [
                'volunteer' => 'Volunteer',
                'donate-support' => 'Donate / Support',
                'partnership' => 'Partnership',
                'general-inquiry' => 'General Inquiry',
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $routeRule = Rule::in(array_keys($this->routeOptions()));
        $iconRule = Rule::in(array_keys($this->iconOptions()));
        $inquiryRule = Rule::in(['volunteer', 'donate-support', 'partnership', 'general-inquiry']);

        $rules = [
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_short_name' => ['required', 'string', 'max:255'],
            'organization_tagline' => ['required', 'string'],
            'organization_registration_blurb' => ['nullable', 'string'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:255'],
            'contact_location' => ['required', 'string', 'max:255'],
            'contact_address' => ['required', 'string'],
            'contact_hours' => ['required', 'string', 'max:255'],
            'contact_map_embed' => ['nullable', 'string'],
            'support_headline' => ['required', 'string'],
            'support_summary' => ['required', 'string'],
            'footer_summary' => ['required', 'string'],
            'footer_trust_line' => ['required', 'string'],
            'footer_donate_note' => ['required', 'string'],
            'seo_default_title' => ['required', 'string', 'max:255'],
            'seo_default_description' => ['required', 'string'],
            'seo_default_keywords' => ['nullable', 'string'],
            'seo_default_og_image' => ['nullable', 'string', 'max:2048'],
            'seo_google_site_verification' => ['nullable', 'string', 'max:255'],
            'seo_ga_measurement_id' => ['nullable', 'string', 'max:50'],

            'social_facebook_handle' => ['nullable', 'string', 'max:255'],
            'social_facebook_href' => ['required', 'url', 'max:2048'],
            'social_youtube_handle' => ['nullable', 'string', 'max:255'],
            'social_youtube_href' => ['required', 'url', 'max:2048'],
            'social_instagram_handle' => ['nullable', 'string', 'max:255'],
            'social_instagram_href' => ['required', 'url', 'max:2048'],

            'nav_home_label' => ['required', 'string', 'max:255'],
            'nav_home_route' => ['required', $routeRule],
            'nav_about_group_label' => ['required', 'string', 'max:255'],
            'nav_about_item_1_label' => ['required', 'string', 'max:255'],
            'nav_about_item_1_route' => ['required', $routeRule],
            'nav_about_item_2_label' => ['required', 'string', 'max:255'],
            'nav_about_item_2_route' => ['required', $routeRule],
            'nav_gallery_group_label' => ['required', 'string', 'max:255'],
            'nav_gallery_item_1_label' => ['required', 'string', 'max:255'],
            'nav_gallery_item_1_route' => ['required', $routeRule],
            'nav_gallery_item_2_label' => ['required', 'string', 'max:255'],
            'nav_gallery_item_2_route' => ['required', $routeRule],
            'nav_gallery_item_3_label' => ['required', 'string', 'max:255'],
            'nav_gallery_item_3_route' => ['required', $routeRule],
            'nav_letters_label' => ['required', 'string', 'max:255'],
            'nav_letters_route' => ['required', $routeRule],
            'nav_contact_label' => ['required', 'string', 'max:255'],
            'nav_contact_route' => ['required', $routeRule],
        ];

        for ($index = 1; $index <= 5; $index++) {
            $rules["quick_link_{$index}_label"] = ['required', 'string', 'max:255'];
            $rules["quick_link_{$index}_route"] = ['required', $routeRule];
        }

        for ($index = 1; $index <= 3; $index++) {
            $rules["support_action_{$index}_label"] = ['required', 'string', 'max:255'];
            $rules["support_action_{$index}_description"] = ['required', 'string'];
            $rules["support_action_{$index}_route"] = ['required', $routeRule];
            $rules["support_action_{$index}_inquiry"] = ['required', $inquiryRule];
            $rules["support_action_{$index}_icon"] = ['required', $iconRule];
        }

        $rules['inquiry_label_volunteer'] = ['required', 'string', 'max:255'];
        $rules['inquiry_label_donate_support'] = ['required', 'string', 'max:255'];
        $rules['inquiry_label_partnership'] = ['required', 'string', 'max:255'];
        $rules['inquiry_label_general_inquiry'] = ['required', 'string', 'max:255'];

        $data = $request->validate($rules);

        $payload = [
            'organization' => [
                'name' => $data['organization_name'],
                'short_name' => $data['organization_short_name'],
                'tagline' => $data['organization_tagline'],
                'registration_blurb' => $data['organization_registration_blurb'],
            ],
            'contact' => [
                'email' => $data['contact_email'],
                'phone' => $data['contact_phone'],
                'location' => $data['contact_location'],
                'address' => $data['contact_address'],
                'hours' => $data['contact_hours'],
                'map_embed' => $data['contact_map_embed'],
            ],
            'socials' => [
                [
                    'label' => 'Facebook',
                    'handle' => $data['social_facebook_handle'],
                    'href' => $data['social_facebook_href'],
                ],
                [
                    'label' => 'YouTube',
                    'handle' => $data['social_youtube_handle'],
                    'href' => $data['social_youtube_href'],
                ],
                [
                    'label' => 'Instagram',
                    'handle' => $data['social_instagram_handle'],
                    'href' => $data['social_instagram_href'],
                ],
            ],
            'navigation' => [
                [
                    'label' => $data['nav_home_label'],
                    'route' => $data['nav_home_route'],
                ],
                [
                    'label' => $data['nav_about_group_label'],
                    'children' => [
                        [
                            'label' => $data['nav_about_item_1_label'],
                            'route' => $data['nav_about_item_1_route'],
                        ],
                        [
                            'label' => $data['nav_about_item_2_label'],
                            'route' => $data['nav_about_item_2_route'],
                        ],
                    ],
                ],
                [
                    'label' => $data['nav_gallery_group_label'],
                    'children' => [
                        [
                            'label' => $data['nav_gallery_item_1_label'],
                            'route' => $data['nav_gallery_item_1_route'],
                        ],
                        [
                            'label' => $data['nav_gallery_item_2_label'],
                            'route' => $data['nav_gallery_item_2_route'],
                        ],
                        [
                            'label' => $data['nav_gallery_item_3_label'],
                            'route' => $data['nav_gallery_item_3_route'],
                        ],
                    ],
                ],
                [
                    'label' => $data['nav_letters_label'],
                    'route' => $data['nav_letters_route'],
                ],
                [
                    'label' => $data['nav_contact_label'],
                    'route' => $data['nav_contact_route'],
                ],
            ],
            'quick_links' => collect(range(1, 5))->map(fn ($index) => [
                'label' => $data["quick_link_{$index}_label"],
                'route' => $data["quick_link_{$index}_route"],
            ])->all(),
            'support' => [
                'headline' => $data['support_headline'],
                'summary' => $data['support_summary'],
                'actions' => collect(range(1, 3))->map(fn ($index) => [
                    'label' => $data["support_action_{$index}_label"],
                    'description' => $data["support_action_{$index}_description"],
                    'href' => $data["support_action_{$index}_route"],
                    'inquiry' => $data["support_action_{$index}_inquiry"],
                    'icon' => $data["support_action_{$index}_icon"],
                ])->all(),
            ],
            'seo' => [
                'default_title' => $data['seo_default_title'],
                'default_description' => $data['seo_default_description'],
                'default_keywords' => collect(explode(',', (string) $data['seo_default_keywords']))
                    ->map(fn (string $keyword) => trim($keyword))
                    ->filter()
                    ->values()
                    ->all(),
                'default_og_image' => $data['seo_default_og_image'] ?: null,
                'google_site_verification' => $data['seo_google_site_verification'] ?: null,
                'ga_measurement_id' => $data['seo_ga_measurement_id'] ?: null,
            ],
            'inquiry_types' => [
                'volunteer' => $data['inquiry_label_volunteer'],
                'donate-support' => $data['inquiry_label_donate_support'],
                'partnership' => $data['inquiry_label_partnership'],
                'general-inquiry' => $data['inquiry_label_general_inquiry'],
            ],
            'footer' => [
                'summary' => $data['footer_summary'],
                'trust_line' => $data['footer_trust_line'],
                'donate_note' => $data['footer_donate_note'],
            ],
        ];

        SiteSetting::query()->updateOrCreate(['id' => 1], ['payload' => $payload]);

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Site settings updated successfully.');
    }

    private function routeOptions(): array
    {
        return [
            'home' => 'Home',
            'about' => 'About',
            'what-we-do' => 'What We Do',
            'projects.index' => 'Projects',
            'gallery.index' => 'Gallery',
            'forums.index' => 'Forums',
            'letters.index' => 'Letters',
            'news.index' => 'News',
            'events.index' => 'Events',
            'contact.index' => 'Contact',
            'support' => 'Support the Mission',
        ];
    }

    private function iconOptions(): array
    {
        return [
            'heart' => 'Heart',
            'users' => 'Users',
            'handshake' => 'Handshake',
            'mail' => 'Mail',
            'leaf' => 'Leaf',
            'shield' => 'Shield',
        ];
    }
}
