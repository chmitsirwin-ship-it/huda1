<?php

namespace App\Filament\Admin\Resources\Hadiths;

use App\Filament\Admin\Resources\Hadiths\Pages\CreateHadith;
use App\Filament\Admin\Resources\Hadiths\Pages\EditHadith;
use App\Filament\Admin\Resources\Hadiths\Pages\ListHadiths;
use App\Filament\Admin\Resources\Hadiths\Schemas\HadithForm;
use App\Filament\Admin\Resources\Hadiths\Tables\HadithsTable;
use App\Models\Hadith;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HadithResource extends Resource
{
    protected static ?string $model = Hadith::class;

    public static function getModelLabel(): string
    {
        return __('Hadith');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Hadiths');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedBookmarkSquare;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Islamic Library');
    }

    public static function form(Schema $schema): Schema
    {
        return HadithForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HadithsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHadiths::route('/'),
            'create' => CreateHadith::route('/create'),
            'edit' => EditHadith::route('/{record}/edit'),
        ];
    }
}
