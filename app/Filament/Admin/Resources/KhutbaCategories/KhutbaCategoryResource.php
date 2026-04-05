<?php

namespace App\Filament\Admin\Resources\KhutbaCategories;

use App\Filament\Admin\Resources\KhutbaCategories\Pages\CreateKhutbaCategory;
use App\Filament\Admin\Resources\KhutbaCategories\Pages\EditKhutbaCategory;
use App\Filament\Admin\Resources\KhutbaCategories\Pages\ListKhutbaCategories;
use App\Filament\Admin\Resources\KhutbaCategories\Schemas\KhutbaCategoryForm;
use App\Filament\Admin\Resources\KhutbaCategories\Tables\KhutbaCategoriesTable;
use App\Models\KhutbaCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KhutbaCategoryResource extends Resource
{
    protected static ?string $model = KhutbaCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getModelLabel(): string
    {
        return __('Khutba Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Khutba Categories');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Islamic Library');
    }

    public static function form(Schema $schema): Schema
    {
        return KhutbaCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KhutbaCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKhutbaCategories::route('/'),
            'create' => CreateKhutbaCategory::route('/create'),
            'edit' => EditKhutbaCategory::route('/{record}/edit'),
        ];
    }
}
