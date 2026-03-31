<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_primary_public_routes_and_detail_pages_render(): void
    {
        $this->seed();

        $featuredNews = NewsPost::published()->latest('published_at')->firstOrFail();
        $featuredLetter = Letter::published()->latest('published_at')->firstOrFail();

        foreach ([
            route('home'),
            route('about'),
            route('what-we-do'),
            route('projects.index'),
            route('gallery.index'),
            route('forums.index'),
            route('letters.index'),
            route('letters.show', $featuredLetter),
            route('news.index'),
            route('news.show', $featuredNews),
            route('events.index'),
            route('contact.index'),
            route('support'),
            route('search.index', ['q' => 'Tacloban']),
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_search_returns_relevant_local_results(): void
    {
        $this->seed();

        $response = $this->get(route('search.index', ['q' => 'PrimeWater']));

        $response->assertOk();
        $response->assertSee('PrimeWater', false);
        $response->assertSee('Request for Clarification on PrimeWater Concession Arrangement', false);
    }

    public function test_future_dated_letters_and_videos_do_not_appear_publicly(): void
    {
        $this->seed();

        $futureLetter = Letter::query()->create([
            'title' => 'Future Letter Record',
            'slug' => 'future-letter-record',
            'category' => 'Audit',
            'topic' => 'Future record',
            'summary' => 'This future letter should stay hidden from public visitors.',
            'body' => 'Future-dated body.',
            'published_at' => now()->addDays(5),
        ]);

        Video::query()->create([
            'title' => 'Future Video Story',
            'slug' => 'future-video-story',
            'summary' => 'This future video should stay hidden from the homepage.',
            'video_url' => 'https://example.com/video',
            'published_at' => now()->addDays(3),
            'is_featured' => true,
        ]);

        $this->get(route('letters.index'))
            ->assertOk()
            ->assertDontSee('Future Letter Record');

        $this->get(route('letters.show', $futureLetter->slug))
            ->assertNotFound();

        $this->get(route('search.index', ['q' => 'Future Letter Record']))
            ->assertOk()
            ->assertDontSee(route('letters.show', $futureLetter), false);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Future Video Story');
    }
}
