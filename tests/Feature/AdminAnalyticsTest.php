<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_html_requests_are_recorded_as_page_views(): void
    {
        Route::middleware('web')->get('/analytics-probe', fn () => response('<html><body>probe</body></html>', 200, ['Content-Type' => 'text/html']));

        $response = $this
            ->withHeader('User-Agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)')
            ->get('/analytics-probe');

        $response->assertOk();

        $this->assertDatabaseHas('page_views', [
            'path' => '/analytics-probe',
            'page_label' => 'Analytics Probe',
            'device_type' => 'Mobile',
        ]);
    }

    public function test_admin_requests_are_not_recorded_as_page_views(): void
    {
        Route::middleware('web')->get('/admin/analytics-probe', fn () => response('<html><body>admin</body></html>', 200, ['Content-Type' => 'text/html']));

        $response = $this
            ->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)')
            ->get('/admin/analytics-probe');

        $response->assertOk();
        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_admin_dashboard_renders_analytics_sections(): void
    {
        PageView::query()->create([
            'visitor_key' => 'visitor-1',
            'session_id' => 'session-1',
            'path' => '/',
            'route_name' => 'home',
            'page_label' => 'Home',
            'referrer_host' => 'Direct / Internal',
            'device_type' => 'Desktop',
            'ip_hash' => str_repeat('a', 64),
            'user_agent' => 'Mozilla/5.0',
            'viewed_at' => now(),
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Visitor Analytics')
            ->assertSee('Most Visited Pages')
            ->assertSee('Real-Time');
    }

    public function test_admin_analytics_page_and_export_are_available(): void
    {
        PageView::query()->create([
            'visitor_key' => 'visitor-2',
            'session_id' => 'session-2',
            'path' => '/news',
            'route_name' => 'news.index',
            'page_label' => 'News',
            'referrer_host' => 'google.com',
            'device_type' => 'Mobile',
            'ip_hash' => str_repeat('b', 64),
            'user_agent' => 'Mozilla/5.0',
            'viewed_at' => now(),
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.analytics.index', ['days' => 7]))
            ->assertOk()
            ->assertSee('Visitor Intelligence')
            ->assertSee('Export CSV');

        $this->actingAs($admin)
            ->get(route('admin.analytics.export', ['days' => 7]))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=utf-8');
    }
}
