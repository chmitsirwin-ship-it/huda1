<?php

use App\Filament\Admin\Resources\Announcements\AnnouncementResource;
use App\Models\Announcement;
use App\Support\LocalizedDate;

it('protects the announcement resource pages', function () {
    $announcement = Announcement::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(AnnouncementResource::class, $announcement));
});

it('lets admins open announcement resource pages and see seeded content', function () {
    $announcement = Announcement::query()->whereNotNull('published_at')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(AnnouncementResource::class, $announcement));

    $this->followingRedirects()
        ->get(AnnouncementResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($announcement->title)
        ->assertSeeText(LocalizedDate::dateTime($announcement->published_at));
});
