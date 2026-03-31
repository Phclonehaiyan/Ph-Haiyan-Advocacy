<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectsController extends Controller
{
    public function __invoke(): View
    {
        return view('projects.index', [
            'page' => Page::published()->where('slug', 'projects')->firstOrFail(),
            'projects' => Project::query()->ordered()->get(),
        ]);
    }
}
