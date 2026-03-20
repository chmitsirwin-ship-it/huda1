<?php

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use App\Models\PrayerTime;
use App\Support\LocalizedDate;

it('protects the prayer time resource pages', function () {
    $prayerTime = PrayerTime::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(PrayerTimeResource::class, $prayerTime));
});

it('lets admins open prayer time resource pages and see localized schedule values', function () {
    $prayerTime = PrayerTime::query()->orderByDesc('date')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(PrayerTimeResource::class, $prayerTime));

    $this->followingRedirects()
        ->get(PrayerTimeResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText('Adjust Iqamah Times')
        ->assertSeeText(LocalizedDate::date($prayerTime->date))
        ->assertSeeText(LocalizedDate::time($prayerTime->fajr_adhan));
});
