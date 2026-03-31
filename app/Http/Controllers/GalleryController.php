<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::query()->orderBy('sort_order')->orderByDesc('taken_at')->get();

        return view('gallery.index', [
            'page' => Page::published()->where('slug', 'gallery')->firstOrFail(),
            'items' => $items,
            'categories' => $items->pluck('category')->unique()->values(),
        ]);
    }
}
