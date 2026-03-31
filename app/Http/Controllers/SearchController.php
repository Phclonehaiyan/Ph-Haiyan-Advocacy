<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $results = collect();

        if ($query !== '') {
            $results = $this->searchAll($query)
                ->sortByDesc('score')
                ->values();
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'resultGroups' => $results->groupBy('type_label'),
        ]);
    }

    private function searchAll(string $query): Collection
    {
        return collect()
            ->merge($this->searchPages($query))
            ->merge($this->searchProjects($query))
            ->merge($this->searchLetters($query))
            ->merge($this->searchNews($query))
            ->merge($this->searchEvents($query))
            ->merge($this->searchGallery($query));
    }

    private function searchPages(string $query): Collection
    {
        $routeMap = [
            'home' => route('home'),
            'about' => route('about'),
            'what-we-do' => route('what-we-do'),
            'projects' => route('projects.index'),
            'gallery' => route('gallery.index'),
            'forums' => route('forums.index'),
            'letters' => route('letters.index'),
            'news' => route('news.index'),
            'events' => route('events.index'),
            'contact' => route('contact.index'),
        ];

        return Page::published()
            ->get()
            ->map(function (Page $page) use ($query, $routeMap): ?array {
                $url = $routeMap[$page->slug] ?? null;

                if (! $url) {
                    return null;
                }

                $text = trim(implode(' ', array_filter([
                    $page->title,
                    $page->subtitle,
                    $page->hero_title,
                    $page->hero_subtitle,
                    $this->flattenContent($page->content ?? []),
                ])));

                $score = $this->scoreText($query, $page->title ?? '', $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'page',
                    'type_label' => 'Pages',
                    'title' => $page->title,
                    'summary' => $this->makeSnippet($text, $query, 170),
                    'meta' => strtoupper(str_replace('-', ' ', $page->slug)),
                    'url' => $url,
                    'score' => $score,
                ];
            })
            ->filter();
    }

    private function searchLetters(string $query): Collection
    {
        return Letter::published()
            ->latest('published_at')
            ->get()
            ->map(function (Letter $letter) use ($query): ?array {
                $text = trim(implode(' ', array_filter([
                    $letter->title,
                    $letter->category,
                    $letter->topic,
                    $letter->summary,
                    Str::limit(strip_tags((string) $letter->body), 900, ''),
                ])));

                $score = $this->scoreText($query, $letter->title, $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'letter',
                    'type_label' => 'Letters',
                    'title' => $letter->title,
                    'summary' => $this->makeSnippet($text, $query, 180),
                    'meta' => trim(($letter->category ? strtoupper($letter->category).' • ' : '').optional($letter->published_at)->format('M d, Y')),
                    'url' => route('letters.show', $letter),
                    'score' => $score + 20,
                ];
            })
            ->filter();
    }

    private function searchProjects(string $query): Collection
    {
        return Project::query()
            ->ordered()
            ->get()
            ->map(function (Project $project) use ($query): ?array {
                $text = trim(implode(' ', array_filter([
                    $project->title,
                    $project->category,
                    $project->summary,
                    $project->description,
                ])));

                $score = $this->scoreText($query, $project->title, $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'project',
                    'type_label' => 'Projects',
                    'title' => $project->title,
                    'summary' => $this->makeSnippet($text, $query, 175),
                    'meta' => trim(($project->category ? strtoupper($project->category).' • ' : '').($project->year ?: 'PROJECT ARCHIVE')),
                    'url' => route('projects.index').'#project-'.$project->slug,
                    'score' => $score + 16,
                ];
            })
            ->filter();
    }

    private function searchNews(string $query): Collection
    {
        return NewsPost::published()
            ->latest('published_at')
            ->get()
            ->map(function (NewsPost $post) use ($query): ?array {
                $text = trim(implode(' ', array_filter([
                    $post->title,
                    $post->category,
                    $post->excerpt,
                    Str::limit(strip_tags((string) $post->content), 600, ''),
                ])));

                $score = $this->scoreText($query, $post->title, $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'news',
                    'type_label' => 'News',
                    'title' => $post->title,
                    'summary' => $this->makeSnippet($text, $query, 170),
                    'meta' => trim(($post->category ? strtoupper($post->category).' • ' : '').optional($post->published_at)->format('M d, Y')),
                    'url' => route('news.show', $post),
                    'score' => $score + 10,
                ];
            })
            ->filter();
    }

    private function searchEvents(string $query): Collection
    {
        return Event::published()
            ->latest('start_at')
            ->get()
            ->map(function (Event $event) use ($query): ?array {
                $text = trim(implode(' ', array_filter([
                    $event->title,
                    $event->category,
                    $event->summary,
                    $event->description,
                    $event->location,
                    $event->venue,
                ])));

                $score = $this->scoreText($query, $event->title, $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'event',
                    'type_label' => 'Events',
                    'title' => $event->title,
                    'summary' => $this->makeSnippet($text, $query, 170),
                    'meta' => trim(($event->category ? strtoupper($event->category).' • ' : '').optional($event->start_at)->format('M d, Y')),
                    'url' => route('events.index').'#event-'.$event->slug,
                    'score' => $score + 8,
                ];
            })
            ->filter();
    }

    private function searchGallery(string $query): Collection
    {
        return GalleryItem::query()
            ->latest('taken_at')
            ->get()
            ->map(function (GalleryItem $item) use ($query): ?array {
                $text = trim(implode(' ', array_filter([
                    $item->title,
                    $item->category,
                    $item->summary,
                ])));

                $score = $this->scoreText($query, $item->title, $text);

                if ($score <= 0) {
                    return null;
                }

                return [
                    'type' => 'gallery',
                    'type_label' => 'Gallery',
                    'title' => $item->title,
                    'summary' => $this->makeSnippet($text, $query, 160),
                    'meta' => trim(($item->category ? strtoupper($item->category).' • ' : '').optional($item->taken_at)->format('M d, Y')),
                    'url' => route('gallery.index').'#gallery-'.$item->slug,
                    'score' => $score + 6,
                ];
            })
            ->filter();
    }

    private function scoreText(string $query, string $title, string $text): int
    {
        $query = Str::lower(trim($query));
        $title = Str::lower($title);
        $text = Str::lower($text);

        if ($query === '' || $text === '') {
            return 0;
        }

        $score = 0;

        if (Str::contains($title, $query)) {
            $score += 140;
        }

        if (Str::contains($text, $query)) {
            $score += 70;
        }

        foreach (preg_split('/\s+/', $query, -1, PREG_SPLIT_NO_EMPTY) as $token) {
            if (Str::length($token) < 2) {
                continue;
            }

            if (Str::contains($title, $token)) {
                $score += 24;
            }

            if (Str::contains($text, $token)) {
                $score += 8;
            }
        }

        return $score;
    }

    private function makeSnippet(string $text, string $query, int $limit = 180): string
    {
        $plainText = trim(preg_replace('/\s+/', ' ', strip_tags($text)));

        if ($plainText === '') {
            return '';
        }

        $position = mb_stripos($plainText, $query);

        if ($position === false) {
            return Str::limit($plainText, $limit);
        }

        $start = max(0, $position - 55);
        $snippet = mb_substr($plainText, $start, $limit);

        return ($start > 0 ? '... ' : '').trim($snippet).(mb_strlen($plainText) > ($start + $limit) ? ' ...' : '');
    }

    private function flattenContent(array $content): string
    {
        $flatten = function ($value) use (&$flatten): string {
            if (is_array($value)) {
                return collect($value)->map(fn ($item) => $flatten($item))->implode(' ');
            }

            return is_scalar($value) ? (string) $value : '';
        };

        return trim($flatten($content));
    }
}
