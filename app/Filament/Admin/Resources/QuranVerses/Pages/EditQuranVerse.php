<?php

namespace App\Filament\Admin\Resources\QuranVerses\Pages;

use App\Filament\Admin\Resources\QuranVerses\QuranVerseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuranVerse extends EditRecord
{
    protected static string $resource = QuranVerseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
