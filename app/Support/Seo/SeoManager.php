<?php

namespace App\Support\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoManager
{
    public static function fromContext(array $context = []): array
    {
        $routeName = request()->route()?->getName();
        $routeDefaults = config('site.seo.route_defaults.'.($routeName ?? ''), []);
        $overrides = Arr::wrap($context['seo'] ?? []);

        $page = $context['page'] ?? null;
        $post = $context['post'] ?? null;
        $letter = $context['letter'] ?? null;

        $title = self::firstFilled(
            $overrides['title'] ?? null,
            $context['pageTitle'] ?? null,
            $routeDefaults['title'] ?? null,
            config('site.seo.default_title')
        );

        $description = self::firstFilled(
            $overrides['description'] ?? null,
            $context['pageDescription'] ?? null,
            $routeDefaults['description'] ?? null,
            config('site.seo.default_description')
        );

        $canonicalUrl = self::firstFilled(
            $overrides['canonical_url'] ?? null,
            $routeDefaults['canonical_url'] ?? null,
            url()->current()
        );

        $image = self::absoluteUrl(self::firstFilled(
            $overrides['image'] ?? null,
            $overrides['og_image'] ?? null,
            $routeDefaults['image'] ?? null,
            self::contentImage($post, $letter, $page),
            config('site.seo.default_og_image')
        ));

        $keywords = $overrides['keywords'] ?? $routeDefaults['keywords'] ?? config('site.seo.default_keywords', []);

        if (is_array($keywords)) {
            $keywords = collect($keywords)
                ->filter(fn ($keyword) => filled($keyword))
                ->implode(', ');
        }

        $type = self::firstFilled(
            $overrides['type'] ?? null,
            $routeDefaults['type'] ?? null,
            $post ? 'article' : 'website'
        );

        $robots = self::firstFilled(
            $overrides['robots'] ?? null,
            $routeDefaults['robots'] ?? null,
            config('site.seo.default_robots')
        );

        return [
            'title' => trim((string) $title),
            'description' => trim((string) $description),
            'keywords' => trim((string) $keywords),
            'canonical_url' => $canonicalUrl,
            'image' => $image,
            'type' => $type,
            'robots' => $robots,
            'site_name' => config('site.organization.name'),
            'twitter_card' => config('site.seo.twitter_card', 'summary_large_image'),
            'twitter_site' => config('site.seo.twitter_site'),
            'published_time' => $post?->published_at?->toIso8601String(),
            'modified_time' => ($post?->updated_at ?? $letter?->updated_at ?? $page?->updated_at)?->toIso8601String(),
        ];
    }

    public static function absoluteUrl(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return url(ltrim($value, '/'));
    }

    private static function contentImage(?Model $post, ?Model $letter, ?Model $page): ?string
    {
        return self::firstFilled(
            $post?->og_image,
            $post?->image,
            $letter?->image,
            $page?->hero_image
        );
    }

    private static function firstFilled(mixed ...$values): mixed
    {
        foreach ($values as $value) {
            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }
}
