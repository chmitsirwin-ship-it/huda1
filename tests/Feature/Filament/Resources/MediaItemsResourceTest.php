<?php

use App\Filament\Admin\Resources\MediaItems\MediaItemResource;
use App\Models\MediaItem;

it('protects the media item resource pages', function () {
    $mediaItem = MediaItem::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(MediaItemResource::class, $mediaItem));
});

it('lets admins open media item resource pages and see seeded media entries', function () {
    $mediaItem = MediaItem::query()->orderBy('sort_order')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(MediaItemResource::class, $mediaItem));

    $this->followingRedirects()
        ->get(MediaItemResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($mediaItem->title)
        ->assertSeeText($mediaItem->collection);
});
