<?php

namespace App\Filament\Admin\Resources\MediaItems;

use App\Filament\Admin\Resources\MediaItems\Pages\CreateMediaItem;
use App\Filament\Admin\Resources\MediaItems\Pages\EditMediaItem;
use App\Filament\Admin\Resources\MediaItems\Pages\ListMediaItems;
use App\Filament\Admin\Resources\MediaItems\Schemas\MediaItemForm;
use App\Filament\Admin\Resources\MediaItems\Tables\MediaItemsTable;
use App\Models\MediaItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MediaItemResource extends Resource
{
    protected static ?string $model = MediaItem::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedPhoto;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Content');
    }

    public static function form(Schema $schema): Schema
    {
        return MediaItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediaItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaItems::route('/'),
            'create' => CreateMediaItem::route('/create'),
            'edit' => EditMediaItem::route('/{record}/edit'),
        ];
    }
}
