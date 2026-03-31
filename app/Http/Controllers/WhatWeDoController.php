<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class WhatWeDoController extends Controller
{
    public function __invoke(): View
    {
        return view('what-we-do.index', [
            'page' => Page::published()->where('slug', 'what-we-do')->firstOrFail(),
            'featuredProjects' => Project::query()
                ->ordered()
                ->take(4)
                ->get(),
        ]);
    }
}
