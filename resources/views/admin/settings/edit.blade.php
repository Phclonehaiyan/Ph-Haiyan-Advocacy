@extends('admin.layouts.app', ['pageTitle' => 'Site Settings'])

@section('content')
    @php
        $socialFields = [
            ['prefix' => 'facebook', 'label' => 'Facebook'],
            ['prefix' => 'youtube', 'label' => 'YouTube'],
            ['prefix' => 'instagram', 'label' => 'Instagram'],
        ];

        $footerLinks = [
            1 => 'First footer link',
            2 => 'Second footer link',
            3 => 'Third footer link',
            4 => 'Fourth footer link',
            5 => 'Fifth footer link',
        ];

        $supportCards = [
            1 => 'Primary support button',
            2 => 'Second support button',
            3 => 'Third support button',
        ];

        $inquiryFields = [
            'volunteer' => 'Volunteer',
            'donate_support' => 'Donate / Support',
            'partnership' => 'Partnership',
            'general_inquiry' => 'General Inquiry',
        ];
    @endphp

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="admin-kicker">Global configuration</div>
                    <h2 class="admin-heading">Manage the site-wide settings used across the public website.</h2>
                    <p class="admin-copy">Everything here uses normal fields and dropdowns so non-technical editors can update the website without touching JSON.</p>
                </div>

                <button type="submit" class="btn-primary">
                    <x-icon name="save" class="h-4 w-4" />
                    Save settings
                </button>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="space-y-6">
                <div class="admin-panel">
                    <div class="admin-kicker">Organization</div>
                    <div class="mt-5 grid gap-4">
                        @foreach ([
                            'organization_name' => ['label' => 'Organization Name', 'rows' => 2],
                            'organization_short_name' => ['label' => 'Short Name', 'rows' => 2],
                            'organization_tagline' => ['label' => 'Tagline', 'rows' => 4],
                            'organization_registration_blurb' => ['label' => 'Registration Blurb', 'rows' => 4],
                        ] as $name => $field)
                            <div>
                                <label for="{{ $name }}" class="admin-label">{{ $field['label'] }}</label>
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] }}" class="admin-input">{{ old($name, $values[$name]) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Contact</div>
                    <div class="mt-5 grid gap-4">
                        @foreach ([
                            'contact_email' => ['label' => 'Email', 'rows' => 2],
                            'contact_phone' => ['label' => 'Phone', 'rows' => 2],
                            'contact_location' => ['label' => 'Location', 'rows' => 2],
                            'contact_address' => ['label' => 'Address', 'rows' => 4],
                            'contact_hours' => ['label' => 'Office Hours', 'rows' => 2],
                            'contact_map_embed' => ['label' => 'Map Embed URL', 'rows' => 4],
                        ] as $name => $field)
                            <div>
                                <label for="{{ $name }}" class="admin-label">{{ $field['label'] }}</label>
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] }}" class="admin-input">{{ old($name, $values[$name]) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Social channels</div>
                    <p class="admin-copy mt-3">Update the live links shown in the header, contact page, and footer. The platform icons stay fixed automatically.</p>
                    <div class="mt-5 grid gap-4">
                        @foreach ($socialFields as $social)
                            <div class="admin-panel-subtle">
                                <div class="admin-label">{{ $social['label'] }}</div>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="social_{{ $social['prefix'] }}_handle" class="admin-label">Handle</label>
                                        <input id="social_{{ $social['prefix'] }}_handle" name="social_{{ $social['prefix'] }}_handle" type="text" value="{{ old('social_' . $social['prefix'] . '_handle', $values['social_' . $social['prefix'] . '_handle']) }}" class="admin-input mt-2">
                                    </div>
                                    <div>
                                        <label for="social_{{ $social['prefix'] }}_href" class="admin-label">Link</label>
                                        <input id="social_{{ $social['prefix'] }}_href" name="social_{{ $social['prefix'] }}_href" type="url" value="{{ old('social_' . $social['prefix'] . '_href', $values['social_' . $social['prefix'] . '_href']) }}" class="admin-input mt-2">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-panel">
                    <div class="admin-kicker">Support and footer</div>
                    <div class="mt-5 grid gap-4">
                        @foreach ([
                            'support_headline' => 'Support Headline',
                            'support_summary' => 'Support Summary',
                            'footer_summary' => 'Footer Summary',
                            'footer_trust_line' => 'Footer Trust Line',
                            'footer_donate_note' => 'Footer Donate Note',
                        ] as $name => $label)
                            <div>
                                <label for="{{ $name }}" class="admin-label">{{ $label }}</label>
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="4" class="admin-input">{{ old($name, $values[$name]) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">SEO and analytics</div>
                    <p class="admin-copy mt-3">These values control default metadata, Google verification, and GA4 without hardcoding credentials into templates.</p>
                    <div class="mt-5 grid gap-4">
                        @foreach ([
                            'seo_default_title' => ['label' => 'Default SEO Title', 'rows' => 3],
                            'seo_default_description' => ['label' => 'Default Meta Description', 'rows' => 4],
                            'seo_default_keywords' => ['label' => 'Default Keywords (comma-separated)', 'rows' => 4],
                            'seo_default_og_image' => ['label' => 'Default Social Image Path', 'rows' => 2],
                            'seo_google_site_verification' => ['label' => 'Google Search Console Verification Code', 'rows' => 2],
                            'seo_ga_measurement_id' => ['label' => 'GA4 Measurement ID', 'rows' => 2],
                        ] as $name => $field)
                            <div>
                                <label for="{{ $name }}" class="admin-label">{{ $field['label'] }}</label>
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] }}" class="admin-input">{{ old($name, $values[$name]) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Header navigation</div>
                    <p class="admin-copy mt-3">Change the labels and choose the destination page for each menu item and dropdown option.</p>
                    <div class="mt-5 space-y-4">
                        <div class="admin-panel-subtle">
                            <div class="admin-label">Home link</div>
                            <div class="mt-4 grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="nav_home_label" class="admin-label">Label</label>
                                    <input id="nav_home_label" name="nav_home_label" type="text" value="{{ old('nav_home_label', $values['nav_home_label']) }}" class="admin-input mt-2">
                                </div>
                                <div>
                                    <label for="nav_home_route" class="admin-label">Destination</label>
                                    <select id="nav_home_route" name="nav_home_route" class="admin-select mt-2">
                                        @foreach ($routeOptions as $routeName => $routeLabel)
                                            <option value="{{ $routeName }}" @selected(old('nav_home_route', $values['nav_home_route']) === $routeName)>{{ $routeLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="admin-panel-subtle space-y-4">
                            <div>
                                <label for="nav_about_group_label" class="admin-label">About dropdown title</label>
                                <input id="nav_about_group_label" name="nav_about_group_label" type="text" value="{{ old('nav_about_group_label', $values['nav_about_group_label']) }}" class="admin-input mt-2">
                            </div>
                            @for ($item = 1; $item <= 2; $item++)
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="nav_about_item_{{ $item }}_label" class="admin-label">Item {{ $item }} label</label>
                                        <input id="nav_about_item_{{ $item }}_label" name="nav_about_item_{{ $item }}_label" type="text" value="{{ old('nav_about_item_' . $item . '_label', $values['nav_about_item_' . $item . '_label']) }}" class="admin-input mt-2">
                                    </div>
                                    <div>
                                        <label for="nav_about_item_{{ $item }}_route" class="admin-label">Item {{ $item }} destination</label>
                                        <select id="nav_about_item_{{ $item }}_route" name="nav_about_item_{{ $item }}_route" class="admin-select mt-2">
                                            @foreach ($routeOptions as $routeName => $routeLabel)
                                                <option value="{{ $routeName }}" @selected(old('nav_about_item_' . $item . '_route', $values['nav_about_item_' . $item . '_route']) === $routeName)>{{ $routeLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="admin-panel-subtle space-y-4">
                            <div>
                                <label for="nav_gallery_group_label" class="admin-label">Gallery dropdown title</label>
                                <input id="nav_gallery_group_label" name="nav_gallery_group_label" type="text" value="{{ old('nav_gallery_group_label', $values['nav_gallery_group_label']) }}" class="admin-input mt-2">
                            </div>
                            @for ($item = 1; $item <= 3; $item++)
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="nav_gallery_item_{{ $item }}_label" class="admin-label">Item {{ $item }} label</label>
                                        <input id="nav_gallery_item_{{ $item }}_label" name="nav_gallery_item_{{ $item }}_label" type="text" value="{{ old('nav_gallery_item_' . $item . '_label', $values['nav_gallery_item_' . $item . '_label']) }}" class="admin-input mt-2">
                                    </div>
                                    <div>
                                        <label for="nav_gallery_item_{{ $item }}_route" class="admin-label">Item {{ $item }} destination</label>
                                        <select id="nav_gallery_item_{{ $item }}_route" name="nav_gallery_item_{{ $item }}_route" class="admin-select mt-2">
                                            @foreach ($routeOptions as $routeName => $routeLabel)
                                                <option value="{{ $routeName }}" @selected(old('nav_gallery_item_' . $item . '_route', $values['nav_gallery_item_' . $item . '_route']) === $routeName)>{{ $routeLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ([
                                ['prefix' => 'letters', 'title' => 'Letters link'],
                                ['prefix' => 'contact', 'title' => 'Contact link'],
                            ] as $item)
                                <div class="admin-panel-subtle">
                                    <div class="admin-label">{{ $item['title'] }}</div>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="nav_{{ $item['prefix'] }}_label" class="admin-label">Label</label>
                                            <input id="nav_{{ $item['prefix'] }}_label" name="nav_{{ $item['prefix'] }}_label" type="text" value="{{ old('nav_' . $item['prefix'] . '_label', $values['nav_' . $item['prefix'] . '_label']) }}" class="admin-input mt-2">
                                        </div>
                                        <div>
                                            <label for="nav_{{ $item['prefix'] }}_route" class="admin-label">Destination</label>
                                            <select id="nav_{{ $item['prefix'] }}_route" name="nav_{{ $item['prefix'] }}_route" class="admin-select mt-2">
                                                @foreach ($routeOptions as $routeName => $routeLabel)
                                                    <option value="{{ $routeName }}" @selected(old('nav_' . $item['prefix'] . '_route', $values['nav_' . $item['prefix'] . '_route']) === $routeName)>{{ $routeLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Footer quick links</div>
                    <p class="admin-copy mt-3">These are the simple text links shown in the footer.</p>
                    <div class="mt-5 space-y-4">
                        @foreach ($footerLinks as $index => $title)
                            <div class="admin-panel-subtle">
                                <div class="admin-label">{{ $title }}</div>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="quick_link_{{ $index }}_label" class="admin-label">Label</label>
                                        <input id="quick_link_{{ $index }}_label" name="quick_link_{{ $index }}_label" type="text" value="{{ old('quick_link_' . $index . '_label', $values['quick_link_' . $index . '_label']) }}" class="admin-input mt-2">
                                    </div>
                                    <div>
                                        <label for="quick_link_{{ $index }}_route" class="admin-label">Destination</label>
                                        <select id="quick_link_{{ $index }}_route" name="quick_link_{{ $index }}_route" class="admin-select mt-2">
                                            @foreach ($routeOptions as $routeName => $routeLabel)
                                                <option value="{{ $routeName }}" @selected(old('quick_link_' . $index . '_route', $values['quick_link_' . $index . '_route']) === $routeName)>{{ $routeLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Support buttons</div>
                    <p class="admin-copy mt-3">Control the three call-to-action buttons used in the public support sections.</p>
                    <div class="mt-5 space-y-4">
                        @foreach ($supportCards as $index => $title)
                            <div class="admin-panel-subtle">
                                <div class="admin-label">{{ $title }}</div>
                                <div class="mt-4 grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="support_action_{{ $index }}_label" class="admin-label">Button Label</label>
                                        <input id="support_action_{{ $index }}_label" name="support_action_{{ $index }}_label" type="text" value="{{ old('support_action_' . $index . '_label', $values['support_action_' . $index . '_label']) }}" class="admin-input mt-2">
                                    </div>
                                    <div>
                                        <label for="support_action_{{ $index }}_icon" class="admin-label">Icon</label>
                                        <select id="support_action_{{ $index }}_icon" name="support_action_{{ $index }}_icon" class="admin-select mt-2">
                                            @foreach ($iconOptions as $iconValue => $iconLabel)
                                                <option value="{{ $iconValue }}" @selected(old('support_action_' . $index . '_icon', $values['support_action_' . $index . '_icon']) === $iconValue)>{{ $iconLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="support_action_{{ $index }}_description" class="admin-label">Description</label>
                                        <textarea id="support_action_{{ $index }}_description" name="support_action_{{ $index }}_description" rows="3" class="admin-input mt-2">{{ old('support_action_' . $index . '_description', $values['support_action_' . $index . '_description']) }}</textarea>
                                    </div>
                                    <div>
                                        <label for="support_action_{{ $index }}_route" class="admin-label">Destination</label>
                                        <select id="support_action_{{ $index }}_route" name="support_action_{{ $index }}_route" class="admin-select mt-2">
                                            @foreach ($routeOptions as $routeName => $routeLabel)
                                                <option value="{{ $routeName }}" @selected(old('support_action_' . $index . '_route', $values['support_action_' . $index . '_route']) === $routeName)>{{ $routeLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="support_action_{{ $index }}_inquiry" class="admin-label">Preselected Inquiry Type</label>
                                        <select id="support_action_{{ $index }}_inquiry" name="support_action_{{ $index }}_inquiry" class="admin-select mt-2">
                                            @foreach ($inquiryOptions as $inquiryValue => $inquiryLabel)
                                                <option value="{{ $inquiryValue }}" @selected(old('support_action_' . $index . '_inquiry', $values['support_action_' . $index . '_inquiry']) === $inquiryValue)>{{ $inquiryLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="admin-panel">
                    <div class="admin-kicker">Inquiry labels</div>
                    <p class="admin-copy mt-3">These labels appear in the contact form and support button preselection.</p>
                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @foreach ($inquiryFields as $suffix => $label)
                            <div>
                                <label for="inquiry_label_{{ $suffix }}" class="admin-label">{{ $label }}</label>
                                <input id="inquiry_label_{{ $suffix }}" name="inquiry_label_{{ $suffix }}" type="text" value="{{ old('inquiry_label_' . $suffix, $values['inquiry_label_' . $suffix]) }}" class="admin-input mt-2">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
