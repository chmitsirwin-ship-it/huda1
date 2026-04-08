<?php

namespace App\Filament\Admin\Resources\SpecialPrayers;

use App\Filament\Admin\Resources\SpecialPrayers\Pages\CreateSpecialPrayer;
use App\Filament\Admin\Resources\SpecialPrayers\Pages\EditSpecialPrayer;
use App\Filament\Admin\Resources\SpecialPrayers\Pages\ListSpecialPrayers;
use App\Filament\Admin\Resources\SpecialPrayers\Schemas\SpecialPrayerForm;
use App\Filament\Admin\Resources\SpecialPrayers\Tables\SpecialPrayersTable;
use App\Models\SpecialPrayer;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpecialPrayerResource extends Resource
{
    protected static ?string $model = SpecialPrayer::class;

    public static function getModelLabel(): string
    {
        return __('Special Prayer');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Special Prayers');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedStar;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Settings');
    }

    public static function form(Schema $schema): Schema
    {
        return SpecialPrayerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpecialPrayersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpecialPrayers::route('/'),
            'create' => CreateSpecialPrayer::route('/create'),
            'edit' => EditSpecialPrayer::route('/{record}/edit'),
        ];
    }
}
