<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_area_redirects_guests_to_login(): void
    {
        $this->seed();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_sign_in_and_access_key_editor_pages(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'phhaiyanadvocacy6500@gmail.com')->firstOrFail();

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'ChangeMe123!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        foreach ([
            route('admin.dashboard'),
            route('admin.settings.edit'),
            route('admin.password.edit'),
            route('admin.page-editors.edit', 'home'),
            route('admin.page-editors.edit', 'about'),
            route('admin.page-editors.edit', 'what-we-do'),
            route('admin.news.index'),
            route('admin.letters.index'),
            route('admin.projects.index'),
            route('admin.forums.index'),
            route('admin.events.index'),
            route('admin.gallery.index'),
            route('admin.videos.index'),
            route('admin.messages.index'),
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_non_admin_users_are_forbidden_from_admin_area(): void
    {
        $this->seed();

        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_delete_contact_messages(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'phhaiyanadvocacy6500@gmail.com')->firstOrFail();
        $message = ContactMessage::query()->create([
            'name' => 'Sample Sender',
            'email' => 'sender@example.com',
            'phone' => '09123456789',
            'organization' => 'Sample Org',
            'inquiry_type' => 'volunteer',
            'subject' => 'Delete me',
            'message' => 'This message should be removable from the admin inbox.',
            'status' => 'new',
            'submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.messages.destroy', $message))
            ->assertRedirect(route('admin.messages.index'));

        $this->assertDatabaseMissing('contact_messages', [
            'id' => $message->id,
        ]);
    }

    public function test_admin_can_update_about_page_ceo_and_mission_content(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'phhaiyanadvocacy6500@gmail.com')->firstOrFail();
        $page = Page::query()->where('slug', 'about-ph-haiyan')->firstOrFail();
        $content = $page->content ?? [];

        $this->actingAs($admin)
            ->put(route('admin.page-editors.update', 'about'), [
                'title' => $page->title,
                'subtitle' => $page->subtitle,
                'hero_eyebrow' => $page->hero_eyebrow,
                'hero_title' => $page->hero_title,
                'hero_subtitle' => $page->hero_subtitle,
                'hero_image' => $page->hero_image,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'who_we_are_1' => data_get($content, 'who_we_are.0'),
                'who_we_are_2' => data_get($content, 'who_we_are.1'),
                'who_we_are_3' => data_get($content, 'who_we_are.2'),
                'who_we_are_image' => data_get($content, 'who_we_are_image'),
                'logo_paragraph_1' => data_get($content, 'logo.0'),
                'logo_paragraph_2' => data_get($content, 'logo.1'),
                'logo_paragraph_3' => data_get($content, 'logo.2'),
                'logo_paragraph_4' => data_get($content, 'logo.3'),
                'ceo_name' => 'Pete L. Ilagan',
                'ceo_role' => 'Founding CEO',
                'ceo_description' => 'Guiding PH Haiyan through community-rooted climate resilience leadership.',
                'ceo_image' => '/uploads/about/ceo-pete-ilagan.png',
                'ceo_highlight_1' => 'He helped turn post-Haiyan concern into sustained civic action.',
                'ceo_highlight_2' => 'His work connected citizens, experts, and public institutions.',
                'ceo_highlight_3' => 'He continues to push for practical resilience systems in Eastern Visayas.',
                'mission_vision_eyebrow' => 'Mission and Vision',
                'mission_vision_heading' => 'The direction shaping PH Haiyan.',
                'mission_vision_description' => 'These statements frame the organization’s present work and long-term horizon.',
                'mission_vision_badge' => 'Guiding Principles',
                'mission_vision_background_image' => '/images/imported/events/event-balugo-watershed.jpg',
                'mission_label' => 'Mission',
                'mission_title' => 'What PH Haiyan is called to do now.',
                'mission_quote' => 'Build resilient systems across Tacloban and Eastern Visayas.',
                'vision_label' => 'Vision',
                'vision_title' => 'What PH Haiyan is working toward.',
                'vision_quote' => 'A Tacloban that stands as a model for climate-smart resilience.',
                'story_1' => data_get($content, 'story.0'),
                'story_2' => data_get($content, 'story.1'),
                'story_3' => data_get($content, 'story.2'),
                'story_4' => data_get($content, 'story.3'),
                'story_5' => data_get($content, 'story.4'),
                'why_heading' => data_get($content, 'why_resilience.heading'),
                'why_paragraph_1' => data_get($content, 'why_resilience.paragraphs.0'),
                'why_paragraph_2' => data_get($content, 'why_resilience.paragraphs.1'),
                'why_paragraph_3' => data_get($content, 'why_resilience.paragraphs.2'),
                'why_paragraph_4' => data_get($content, 'why_resilience.paragraphs.3'),
                'cta_heading' => data_get($content, 'cta.heading'),
                'cta_description' => data_get($content, 'cta.description'),
            ])
            ->assertRedirect(route('admin.page-editors.edit', 'about'));

        $page->refresh();

        $this->assertSame('Founding CEO', data_get($page->content, 'ceo.role'));
        $this->assertSame('Guiding Principles', data_get($page->content, 'mission_vision.badge'));
        $this->assertSame('Build resilient systems across Tacloban and Eastern Visayas.', data_get($page->content, 'mission_vision.mission_quote'));
        $this->assertSame('A Tacloban that stands as a model for climate-smart resilience.', data_get($page->content, 'mission_vision.vision_quote'));
    }

    public function test_admin_activity_editor_rejects_invalid_json_without_server_error(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'phhaiyanadvocacy6500@gmail.com')->firstOrFail();

        $response = $this->actingAs($admin)
            ->from(route('admin.resources.create', 'activities'))
            ->post(route('admin.resources.store', 'activities'), [
                'title' => 'Testing Activity',
                'slug' => 'testing-activity',
                'category' => 'Field Visit',
                'summary' => 'Testing invalid JSON handling.',
                'content' => '{"highlights":["One","Two",]}',
                'location' => 'Tacloban',
                'image' => '/images/imported/gallery/first-interagency-meeting.jpg',
                'is_featured' => '0',
                'activity_date' => now()->format('Y-m-d\TH:i'),
            ]);

        $response
            ->assertRedirect(route('admin.resources.create', 'activities'))
            ->assertSessionHasErrors('content');
    }

    public function test_admin_activity_editor_shows_json_template_guidance(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'phhaiyanadvocacy6500@gmail.com')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.resources.create', 'activities'))
            ->assertOk()
            ->assertSee('First key takeaway', false)
            ->assertSee('Use valid JSON.', false);
    }
}
