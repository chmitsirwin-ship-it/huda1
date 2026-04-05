<?php

namespace App\Filament\Admin\Resources\KhutbaCategories\Pages;

use App\Filament\Admin\Resources\KhutbaCategories\KhutbaCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKhutbaCategories extends ListRecords
{
    protected static string $resource = KhutbaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
