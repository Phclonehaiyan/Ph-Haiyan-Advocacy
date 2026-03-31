<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        return view('about.index', [
            'page' => Page::published()->where('slug', 'about-ph-haiyan')->firstOrFail(),
        ]);
    }
}
