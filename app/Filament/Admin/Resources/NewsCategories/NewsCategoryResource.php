<?php

namespace App\Filament\Admin\Resources\NewsCategories;

use App\Filament\Admin\Resources\NewsCategories\Pages\CreateNewsCategory;
use App\Filament\Admin\Resources\NewsCategories\Pages\EditNewsCategory;
use App\Filament\Admin\Resources\NewsCategories\Pages\ListNewsCategories;
use App\Filament\Admin\Resources\NewsCategories\Schemas\NewsCategoryForm;
use App\Filament\Admin\Resources\NewsCategories\Tables\NewsCategoriesTable;
use App\Models\NewsCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsCategoryResource extends Resource
{
    protected static ?string $model = NewsCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function getModelLabel(): string
    {
        return __('News Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('News Categories');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Content');
    }

    public static function form(Schema $schema): Schema
    {
        return NewsCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsCategories::route('/'),
            'create' => CreateNewsCategory::route('/create'),
            'edit' => EditNewsCategory::route('/{record}/edit'),
        ];
    }
}
