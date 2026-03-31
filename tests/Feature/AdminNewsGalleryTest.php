<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_supporting_gallery_images_for_news_posts(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => 'Gallery test story',
            'category' => 'News',
            'excerpt' => 'Short summary.',
            'content' => '<p>Full story body.</p>',
            'image' => '/images/test-preview.jpg',
            'is_featured' => '0',
            'is_published' => '1',
            'gallery_images' => [
                [
                    'image' => '/images/news/supporting-1.jpg',
                    'image_alt' => 'Supporting image one',
                    'caption' => 'First supporting image',
                ],
                [
                    'image' => '/images/news/supporting-2.jpg',
                    'image_alt' => 'Supporting image two',
                    'caption' => 'Second supporting image',
                ],
            ],
        ]);

        $response->assertRedirect();

        $post = NewsPost::query()->where('slug', 'gallery-test-story')->firstOrFail();

        $this->assertCount(2, $post->galleryImages);
        $this->assertDatabaseHas('news_post_images', [
            'news_post_id' => $post->id,
            'image' => '/images/news/supporting-1.jpg',
            'caption' => 'First supporting image',
            'sort_order' => 0,
        ]);
    }

    public function test_news_story_page_renders_supporting_gallery_images(): void
    {
        Page::query()->create([
            'slug' => 'news',
            'title' => 'News',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $post = NewsPost::query()->create([
            'title' => 'Story with gallery',
            'slug' => 'story-with-gallery',
            'category' => 'News',
            'excerpt' => 'Summary',
            'content' => '<p>Body</p>',
            'image' => '/images/preview.jpg',
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $post->galleryImages()->create([
            'image' => '/images/news/supporting-1.jpg',
            'image_alt' => 'Supporting image one',
            'caption' => 'First supporting image',
            'sort_order' => 0,
        ]);

        $this->get(route('news.show', $post))
            ->assertOk()
            ->assertSee('Supporting images from the field and archive.')
            ->assertSee('First supporting image')
            ->assertSee('/images/news/supporting-1.jpg');
    }
}
