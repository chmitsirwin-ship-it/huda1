<?php

namespace App\Filament\Admin\Resources\Hadiths\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HadithsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('narrator')
                    ->label(__('Narrator'))
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->getTranslation('narrator', app()->getLocale(), false) ?: $record->getTranslation('narrator', 'en', false)),

                TextColumn::make('source')
                    ->label(__('Source'))
                    ->getStateUsing(fn ($record) => $record->getTranslation('source', app()->getLocale(), false) ?: $record->getTranslation('source', 'en', false)),

                TextColumn::make('collection')
                    ->label(__('Collection')),

                ToggleColumn::make('is_featured')
                    ->label(__('Featured')),
            ])
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
