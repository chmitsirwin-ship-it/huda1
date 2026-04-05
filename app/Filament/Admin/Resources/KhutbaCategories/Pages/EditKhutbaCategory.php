<?php

namespace App\Filament\Admin\Resources\KhutbaCategories\Pages;

use App\Filament\Admin\Resources\KhutbaCategories\KhutbaCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKhutbaCategory extends EditRecord
{
    protected static string $resource = KhutbaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
