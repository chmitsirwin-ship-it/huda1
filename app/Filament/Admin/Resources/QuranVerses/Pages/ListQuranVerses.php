<?php

namespace App\Filament\Admin\Resources\QuranVerses\Pages;

use App\Filament\Admin\Resources\QuranVerses\QuranVerseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuranVerses extends ListRecords
{
    protected static string $resource = QuranVerseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
