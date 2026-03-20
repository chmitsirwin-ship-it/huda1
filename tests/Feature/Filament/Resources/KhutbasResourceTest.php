<?php

use App\Filament\Admin\Resources\Khutbas\KhutbaResource;
use App\Models\Khutba;
use App\Support\LocalizedDate;

it('protects the khutba resource pages', function () {
    $khutba = Khutba::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(KhutbaResource::class, $khutba));
});

it('lets admins open khutba resource pages and see seeded khutbas', function () {
    $khutba = Khutba::query()->orderByDesc('date')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(KhutbaResource::class, $khutba));

    $this->followingRedirects()
        ->get(KhutbaResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($khutba->title)
        ->assertSeeText($khutba->speaker)
        ->assertSeeText(LocalizedDate::date($khutba->date));
});
