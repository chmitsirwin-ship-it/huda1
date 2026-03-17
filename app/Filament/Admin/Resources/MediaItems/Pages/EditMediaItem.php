<?php

namespace App\Filament\Admin\Resources\MediaItems\Pages;

use App\Filament\Admin\Resources\MediaItems\MediaItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMediaItem extends EditRecord
{
    protected static string $resource = MediaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
