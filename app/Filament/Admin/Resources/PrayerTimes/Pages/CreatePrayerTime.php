<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Pages;

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrayerTime extends CreateRecord
{
    protected static string $resource = PrayerTimeResource::class;
}
