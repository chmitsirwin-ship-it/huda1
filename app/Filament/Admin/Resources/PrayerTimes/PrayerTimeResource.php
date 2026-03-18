<?php

namespace App\Filament\Admin\Resources\PrayerTimes;

use App\Filament\Admin\Resources\PrayerTimes\Pages\CreatePrayerTime;
use App\Filament\Admin\Resources\PrayerTimes\Pages\EditPrayerTime;
use App\Filament\Admin\Resources\PrayerTimes\Pages\ListPrayerTimes;
use App\Filament\Admin\Resources\PrayerTimes\Schemas\PrayerTimeForm;
use App\Filament\Admin\Resources\PrayerTimes\Tables\PrayerTimesTable;
use App\Models\PrayerTime;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrayerTimeResource extends Resource
{
    protected static ?string $model = PrayerTime::class;

    public static function getModelLabel(): string
    {
        return __('Prayer Time');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Prayer Times');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedClock;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Settings');
    }

    public static function form(Schema $schema): Schema
    {
        return PrayerTimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrayerTimesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrayerTimes::route('/'),
            'create' => CreatePrayerTime::route('/create'),
            'edit' => EditPrayerTime::route('/{record}/edit'),
        ];
    }
}
