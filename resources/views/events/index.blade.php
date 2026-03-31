@extends('layouts.app', ['pageTitle' => $page->meta_title, 'pageDescription' => $page->meta_description])

@section('content')
    <x-hero
        :eyebrow="$page->hero_eyebrow"
        :title="$page->hero_title"
        :description="$page->hero_subtitle"
        :image="$page->hero_image"
        :compact="true"
    />

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Upcoming Events"
            title="Confirmed public schedules and upcoming gatherings."
            description="As new PH Haiyan forums, meetings, and field activities are confirmed, they will appear here."
        />

        <div class="mt-12 grid gap-6 xl:grid-cols-3">
            @forelse ($upcomingEvents as $event)
                <x-cards.event :event="$event" />
            @empty
                <div class="surface-card xl:col-span-3 text-sm leading-7 text-slate-600">
                    No upcoming public schedule has been posted yet.
                </div>
            @endforelse
        </div>
    </section>

    <section class="section-shell py-12 lg:py-16">
        <x-section-header
            eyebrow="Past Events"
            title="A record of recent gatherings, forums, and field activities."
            description="Archive highlights carry fuller notes preserved from PH Haiyan's earlier website, so the older project cards on the homepage can open into a more complete story here."
        />

        <div class="mt-12 space-y-6">
            @forelse ($pastEvents as $event)
                <x-cards.event :event="$event" />
            @empty
                <div class="surface-card text-sm leading-7 text-slate-600">
                    No past events have been archived yet.
                </div>
            @endforelse
        </div>
    </section>
@endsection
