<?php

namespace App\Filament\Admin\Resources\Khutbas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class KhutbasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale(), false) ?: $record->getTranslation('title', 'en', false)),

                TextColumn::make('speaker')
                    ->label(__('Speaker'))
                    ->getStateUsing(fn ($record) => $record->getTranslation('speaker', app()->getLocale(), false) ?: $record->getTranslation('speaker', 'en', false)),

                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),

                ToggleColumn::make('is_published')
                    ->label(__('Published')),
            ])
            ->defaultSort('date', 'desc')
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
