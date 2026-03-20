<?php

use App\Enums\EventStatus;
use App\Models\Event;
use App\Support\LocalizedDate;

it('lists only published events on the public index', function () {
    $publishedEvents = Event::query()
        ->where('status', EventStatus::Published)
        ->orderBy('starts_at')
        ->get();

    $draftEvent = Event::query()->where('status', EventStatus::Draft)->firstOrFail();

    $response = $this->get(route('events.index'))
        ->assertOk();

    foreach ($publishedEvents as $event) {
        $response->assertSeeText($event->title);
    }

    $response->assertDontSeeText($draftEvent->title);
});

it('renders a published event detail with localized schedule information', function () {
    $event = Event::query()->where('status', EventStatus::Published)->orderBy('starts_at')->firstOrFail();

    $this->get(route('events.show', $event))
        ->assertOk()
        ->assertSeeText($event->title)
        ->assertSeeText(LocalizedDate::date($event->starts_at))
        ->assertSeeText(LocalizedDate::time($event->starts_at))
        ->assertSeeText($event->location);
});

it('returns 404 for draft event details', function () {
    $draftEvent = Event::query()->where('status', EventStatus::Draft)->firstOrFail();

    $this->get(route('events.show', $draftEvent))
        ->assertNotFound();
});
