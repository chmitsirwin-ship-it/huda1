<?php

namespace App\Filament\Admin\Resources\SpecialPrayers\Pages;

use App\Filament\Admin\Resources\SpecialPrayers\SpecialPrayerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecialPrayer extends EditRecord
{
    protected static string $resource = SpecialPrayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
