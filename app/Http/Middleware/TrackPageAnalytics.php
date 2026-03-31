<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Models\Event;
use App\Models\ForumTopic;
use App\Models\GalleryItem;
use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\PageView;
use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageAnalytics
{
    private const VISITOR_COOKIE = 'phh_visitor';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        if (! Schema::hasTable('page_views')) {
            return $response;
        }

        $visitorKey = (string) ($request->cookie(self::VISITOR_COOKIE) ?: Str::uuid());

        if (! $request->hasCookie(self::VISITOR_COOKIE)) {
            $response->headers->setCookie(
                Cookie::make(self::VISITOR_COOKIE, $visitorKey, 60 * 24 * 365, '/', null, $request->isSecure(), false, false, 'lax')
            );
        }

        PageView::query()->create([
            'visitor_key' => $visitorKey,
            'session_id' => $request->session()->getId(),
            'path' => '/'.ltrim($request->path(), '/'),
            'route_name' => $request->route()?->getName(),
            'page_label' => $this->resolvePageLabel($request),
            'referrer_host' => $this->resolveReferrerHost($request),
            'device_type' => $this->resolveDeviceType((string) $request->userAgent()),
            'ip_hash' => hash('sha256', implode('|', [$request->ip(), config('app.key')])),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'viewed_at' => now(),
        ]);

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $routeName = $request->route()?->getName();

        if ($routeName !== null && Str::startsWith($routeName, 'admin.')) {
            return false;
        }

        if ($request->is('admin') || $request->is('admin/*')) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return false;
        }

        $userAgent = Str::lower((string) $request->userAgent());

        if ($userAgent === '' || preg_match('/bot|spider|crawl|slurp|bingpreview|facebookexternalhit|monitoring|uptimerobot/', $userAgent)) {
            return false;
        }

        return true;
    }

    private function resolveReferrerHost(Request $request): ?string
    {
        $referer = $request->headers->get('referer');

        if (! filled($referer)) {
            return null;
        }

        $host = parse_url($referer, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return null;
        }

        $currentHost = parse_url(config('app.url'), PHP_URL_HOST);

        return $host === $currentHost ? 'Direct / Internal' : $host;
    }

    private function resolveDeviceType(string $userAgent): string
    {
        $userAgent = Str::lower($userAgent);

        if (preg_match('/ipad|tablet|kindle|playbook|silk/', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/iphone|ipod|android.*mobile|windows phone|mobile/', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private function resolvePageLabel(Request $request): string
    {
        return match ($request->route()?->getName()) {
            'home' => 'Home',
            'about' => 'About PH Haiyan',
            'what-we-do' => 'What We Do',
            'projects.index' => 'Projects',
            'gallery.index' => 'Gallery',
            'forums.index' => 'Forums',
            'letters.index' => 'Letters',
            'letters.show' => optional($request->route('letter'))->title ?: 'Letter',
            'news.index' => 'News',
            'news.show' => optional($request->route('newsPost'))->title ?: 'News Story',
            'events.index' => 'Events',
            'contact.index' => 'Contact',
            'search.index' => 'Search',
            'support' => 'Support the Mission',
            default => $this->resolveFallbackLabel($request),
        };
    }

    private function resolveFallbackLabel(Request $request): string
    {
        foreach ([
            $request->route('project') instanceof Project ? $request->route('project')->title : null,
            $request->route('forumTopic') instanceof ForumTopic ? $request->route('forumTopic')->title : null,
            $request->route('event') instanceof Event ? $request->route('event')->title : null,
            $request->route('activity') instanceof Activity ? $request->route('activity')->title : null,
            $request->route('galleryItem') instanceof GalleryItem ? $request->route('galleryItem')->title : null,
            $request->route('letter') instanceof Letter ? $request->route('letter')->title : null,
            $request->route('newsPost') instanceof NewsPost ? $request->route('newsPost')->title : null,
        ] as $label) {
            if (filled($label)) {
                return $label;
            }
        }

        return Str::headline(trim($request->path(), '/')) ?: 'Home';
    }
}
