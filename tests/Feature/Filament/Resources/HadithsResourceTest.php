<?php

use App\Filament\Admin\Resources\Hadiths\HadithResource;
use App\Models\Hadith;

it('protects the hadith resource pages', function () {
    $hadith = Hadith::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(HadithResource::class, $hadith));
});

it('lets admins open hadith resource pages and see seeded hadith records', function () {
    $hadith = Hadith::query()->firstOrFail();

    assertAdminCanOpen(adminPageUrls(HadithResource::class, $hadith));

    $this->followingRedirects()
        ->get(HadithResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($hadith->narrator)
        ->assertSeeText($hadith->source)
        ->assertSeeText($hadith->collection);
});
