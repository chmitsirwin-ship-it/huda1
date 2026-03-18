<?php

namespace App\Filament\Admin\Resources\Sliders;

use App\Filament\Admin\Resources\Sliders\Pages\CreateSlider;
use App\Filament\Admin\Resources\Sliders\Pages\EditSlider;
use App\Filament\Admin\Resources\Sliders\Pages\ListSliders;
use App\Filament\Admin\Resources\Sliders\Schemas\SliderForm;
use App\Filament\Admin\Resources\Sliders\Tables\SlidersTable;
use App\Models\Slider;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    public static function getModelLabel(): string
    {
        return __('Slider');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Sliders');
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedViewColumns;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Content');
    }

    public static function form(Schema $schema): Schema
    {
        return SliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlidersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}
