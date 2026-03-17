<?php

namespace App\Filament\Admin\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale(), false) ?: $record->getTranslation('title', 'en', false)),

                TextColumn::make('starts_at')
                    ->label(__('Starts At'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),

                ToggleColumn::make('is_featured')
                    ->label(__('Featured')),
            ])
            ->defaultSort('starts_at', 'desc')
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
