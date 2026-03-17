<?php

namespace App\Filament\Admin\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label(__('Photo'))
                    ->circular(),

                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->getTranslation('name', app()->getLocale(), false) ?: $record->getTranslation('name', 'en', false)),

                TextColumn::make('title')
                    ->label(__('Title'))
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale(), false) ?: $record->getTranslation('title', 'en', false)),

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
