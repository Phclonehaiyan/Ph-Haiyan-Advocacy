<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class EventsController extends Controller
{
    public function index(): View
    {
        $events = Event::published()->orderBy('start_at')->get();

        return view('events.index', [
            'page' => Page::published()->where('slug', 'events')->firstOrFail(),
            'upcomingEvents' => $events->where('start_at', '>=', now()->startOfDay())->values(),
            'pastEvents' => $events->where('start_at', '<', now()->startOfDay())->sortByDesc('start_at')->values(),
        ]);
    }
}
