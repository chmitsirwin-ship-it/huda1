<?php

use App\Filament\Admin\Resources\Languages\LanguageResource;
use App\Models\Language;

it('protects the language resource pages', function () {
    $language = Language::query()->where('code', 'ar')->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(LanguageResource::class, $language));
});

it('lets admins open language resource pages and see seeded languages', function () {
    $language = Language::query()->where('code', 'ar')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(LanguageResource::class, $language));

    $this->followingRedirects()
        ->get(LanguageResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText('English')
        ->assertSeeText('العربية');
});
