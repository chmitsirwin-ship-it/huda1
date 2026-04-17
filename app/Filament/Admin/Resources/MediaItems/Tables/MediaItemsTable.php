<?php

namespace App\Filament\Admin\Resources\MediaItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TinusG\FilamentHoverImageColumn\HoverImageColumn;

class MediaItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                HoverImageColumn::make('file_path')
                    ->label(__('Image'))
                    ->square(),

                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),

                TextColumn::make('collection')
                    ->label(__('Collection')),

                TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
