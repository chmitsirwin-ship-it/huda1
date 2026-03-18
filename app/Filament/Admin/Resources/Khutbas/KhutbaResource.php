<?php

namespace App\Filament\Admin\Resources\Khutbas;

use App\Filament\Admin\Resources\Khutbas\Pages\CreateKhutba;
use App\Filament\Admin\Resources\Khutbas\Pages\EditKhutba;
use App\Filament\Admin\Resources\Khutbas\Pages\ListKhutbas;
use App\Filament\Admin\Resources\Khutbas\Schemas\KhutbaForm;
use App\Filament\Admin\Resources\Khutbas\Tables\KhutbasTable;
use App\Models\Khutba;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KhutbaResource extends Resource
{
    protected static ?string $model = Khutba::class;

    public static function getModelLabel(): string
    {
        return __('Khutba');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Khutbas');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedMicrophone;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Islamic Library');
    }

    public static function form(Schema $schema): Schema
    {
        return KhutbaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KhutbasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKhutbas::route('/'),
            'create' => CreateKhutba::route('/create'),
            'edit' => EditKhutba::route('/{record}/edit'),
        ];
    }
}
