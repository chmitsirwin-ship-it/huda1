<?php

use App\Filament\Admin\Resources\Events\EventResource;
use App\Models\Event;
use App\Support\LocalizedDate;

it('protects the event resource pages', function () {
    $event = Event::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(EventResource::class, $event));
});

it('lets admins open event resource pages and see seeded schedules', function () {
    $event = Event::query()->orderBy('starts_at')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(EventResource::class, $event));

    $this->followingRedirects()
        ->get(EventResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($event->title)
        ->assertSeeText(LocalizedDate::dateTime($event->starts_at));
});
