<?php

use App\Filament\Admin\Resources\QuranVerses\QuranVerseResource;
use App\Models\QuranVerse;

it('protects the quran verse resource pages', function () {
    $verse = QuranVerse::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(QuranVerseResource::class, $verse));
});

it('lets admins open quran verse resource pages and see seeded verse references', function () {
    $verse = QuranVerse::query()->orderBy('surah_number')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(QuranVerseResource::class, $verse));

    $this->followingRedirects()
        ->get(QuranVerseResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($verse->surah_name)
        ->assertSeeText((string) $verse->surah_number)
        ->assertSeeText((string) $verse->verse_number);
});
