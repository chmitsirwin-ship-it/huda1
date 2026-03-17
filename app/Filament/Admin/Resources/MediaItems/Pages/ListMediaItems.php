<?php

namespace App\Filament\Admin\Resources\MediaItems\Pages;

use App\Filament\Admin\Resources\MediaItems\MediaItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMediaItems extends ListRecords
{
    protected static string $resource = MediaItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
