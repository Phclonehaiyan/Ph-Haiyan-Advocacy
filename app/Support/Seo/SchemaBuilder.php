<?php

namespace App\Support\Seo;

class SchemaBuilder
{
    public static function forContext(array $seo, array $context = []): array
    {
        $schemas = [self::website()];

        if (request()->routeIs('home')) {
            $schemas[] = self::organization();
        }

        if (($post = $context['post'] ?? null) && request()->routeIs('news.show')) {
            $schemas[] = self::article($post, $seo);
        }

        return array_values(array_filter($schemas));
    }

    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('site.organization.name'),
            'url' => url('/'),
            'description' => config('site.seo.default_description'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('search.index').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public static function organization(): array
    {
        $socials = collect(config('site.socials', []))
            ->pluck('href')
            ->filter()
            ->values()
            ->all();

        $organization = [
            '@context' => 'https://schema.org',
            '@type' => config('site.seo.organization_type', 'Organization'),
            'name' => config('site.organization.name'),
            'alternateName' => config('site.organization.short_name'),
            'url' => url('/'),
            'logo' => SeoManager::absoluteUrl(config('site.seo.organization_logo')),
            'image' => SeoManager::absoluteUrl(config('site.seo.default_og_image')),
            'description' => config('site.seo.default_description'),
            'areaServed' => config('site.seo.area_served'),
            'sameAs' => $socials,
        ];

        if (filled(config('site.seo.founding_date'))) {
            $organization['foundingDate'] = config('site.seo.founding_date');
        }

        if (filled(config('site.contact.phone')) || filled(config('site.contact.email'))) {
            $organization['contactPoint'] = [[
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'telephone' => config('site.contact.phone'),
                'email' => config('site.contact.email'),
                'areaServed' => config('site.seo.area_served'),
                'availableLanguage' => ['en', 'fil'],
            ]];
        }

        return array_filter($organization, fn ($value) => filled($value) || is_array($value));
    }

    public static function article(object $post, array $seo): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $seo['title'],
            'description' => $seo['description'],
            'url' => $seo['canonical_url'],
            'image' => array_values(array_filter([$seo['image']])),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'articleSection' => $post->category,
            'author' => [
                '@type' => 'Organization',
                'name' => config('site.organization.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('site.organization.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => SeoManager::absoluteUrl(config('site.seo.organization_logo')),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $seo['canonical_url'],
            ],
        ];
    }
}
