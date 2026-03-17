<?php

namespace App\Filament\Admin\Resources\Khutbas\Pages;

use App\Filament\Admin\Resources\Khutbas\KhutbaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKhutba extends EditRecord
{
    protected static string $resource = KhutbaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
