<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class SupportController extends Controller
{
    public function __invoke(): View
    {
        return view('support.index', [
            'page' => Page::published()->where('slug', 'support')->firstOrFail(),
        ]);
    }
}
