<?php

namespace App\Filament\Admin\Resources\QuranVerses;

use App\Filament\Admin\Resources\QuranVerses\Pages\CreateQuranVerse;
use App\Filament\Admin\Resources\QuranVerses\Pages\EditQuranVerse;
use App\Filament\Admin\Resources\QuranVerses\Pages\ListQuranVerses;
use App\Filament\Admin\Resources\QuranVerses\Schemas\QuranVerseForm;
use App\Filament\Admin\Resources\QuranVerses\Tables\QuranVersesTable;
use App\Models\QuranVerse;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuranVerseResource extends Resource
{
    protected static ?string $model = QuranVerse::class;

    public static function getModelLabel(): string
    {
        return __('Quran Verse');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Quran Verses');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedBookOpen;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Islamic Library');
    }

    public static function form(Schema $schema): Schema
    {
        return QuranVerseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuranVersesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuranVerses::route('/'),
            'create' => CreateQuranVerse::route('/create'),
            'edit' => EditQuranVerse::route('/{record}/edit'),
        ];
    }
}
