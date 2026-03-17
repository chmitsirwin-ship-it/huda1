<?php

namespace App\Filament\Admin\Resources\Hadiths\Pages;

use App\Filament\Admin\Resources\Hadiths\HadithResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHadith extends EditRecord
{
    protected static string $resource = HadithResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
