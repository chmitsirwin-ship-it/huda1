<?php

namespace App\Filament\Admin\Resources\Khutbas\Pages;

use App\Filament\Admin\Resources\Khutbas\KhutbaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKhutbas extends ListRecords
{
    protected static string $resource = KhutbaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
