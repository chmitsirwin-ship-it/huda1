<?php

use App\Models\PrayerTime;
use App\Support\LocalizedDate;

it('renders today prayer times with localized date and time strings', function () {
    $today = PrayerTime::query()->whereDate('date', today())->firstOrFail();

    $this->get(route('prayer-times.index'))
        ->assertOk()
        ->assertSeeText("Today's Prayer Times")
        ->assertSeeText(LocalizedDate::date($today->date))
        ->assertSeeText(LocalizedDate::time($today->fajr_adhan))
        ->assertSeeText(LocalizedDate::time($today->dhuhr_adhan));
});

it('renders a requested month and exposes the month data to the view', function () {
    $firstPrayer = PrayerTime::query()->orderBy('date')->firstOrFail();

    $this->get(route('prayer-times.index', [
        'year' => $firstPrayer->date->year,
        'month' => $firstPrayer->date->month,
    ]))
        ->assertOk()
        ->assertViewHas('year', $firstPrayer->date->year)
        ->assertViewHas('month', $firstPrayer->date->month)
        ->assertViewHas('prayerTimes', function ($prayerTimes) use ($firstPrayer) {
            return $prayerTimes->count() === PrayerTime::query()
                ->whereYear('date', $firstPrayer->date->year)
                ->whereMonth('date', $firstPrayer->date->month)
                ->count();
        })
        ->assertSeeText(LocalizedDate::monthYear($firstPrayer->date));
});
