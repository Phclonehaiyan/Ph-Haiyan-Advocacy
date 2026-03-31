<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $this->seed();

        foreach ([
            route('home'),
            route('about'),
            route('what-we-do'),
            route('gallery.index'),
            route('forums.index'),
            route('letters.index'),
            route('news.index'),
            route('events.index'),
            route('support'),
            route('contact.index'),
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }
}
