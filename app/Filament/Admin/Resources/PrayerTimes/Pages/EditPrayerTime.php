<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Pages;

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrayerTime extends EditRecord
{
    protected static string $resource = PrayerTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
