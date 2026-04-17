<?php

namespace App\Filament\Admin\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use TinusG\FilamentHoverImageColumn\HoverImageColumn;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                HoverImageColumn::make('photo')
                    ->label(__('Photo'))
                    ->circular(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('Title')),

                ToggleColumn::make('is_active')
                    ->label(__('Active')),

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
