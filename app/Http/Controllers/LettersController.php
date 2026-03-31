<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class LettersController extends Controller
{
    public function index(): View
    {
        $letters = Letter::published()->latest('published_at')->get();

        return view('letters.index', [
            'page' => Page::published()->where('slug', 'letters')->firstOrFail(),
            'letters' => $letters,
            'featuredLetter' => $letters->firstWhere('is_featured', true) ?? $letters->first(),
            'categories' => $letters->pluck('category')->unique()->values(),
        ]);
    }

    public function show(Letter $letter): View
    {
        $letter = Letter::published()->whereKey($letter->getKey())->firstOrFail();
        $targetRelatedCount = 3;

        $primaryRelated = Letter::published()
            ->whereKeyNot($letter->getKey())
            ->where(function ($query) use ($letter): void {
                $query->where('topic', $letter->topic)
                    ->orWhere('category', $letter->category);
            })
            ->latest('published_at')
            ->limit($targetRelatedCount)
            ->get();

        $relatedLetters = $primaryRelated;

        if ($relatedLetters->count() < $targetRelatedCount) {
            $fallbackLetters = Letter::published()
                ->whereKeyNot($letter->getKey())
                ->whereNotIn('id', $relatedLetters->pluck('id'))
                ->latest('published_at')
                ->limit($targetRelatedCount - $relatedLetters->count())
                ->get();

            $relatedLetters = $relatedLetters->concat($fallbackLetters);
        }

        return view('letters.show', [
            'page' => Page::published()->where('slug', 'letters')->firstOrFail(),
            'letter' => $letter,
            'relatedLetters' => $relatedLetters,
        ]);
    }
}
