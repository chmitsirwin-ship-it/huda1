<?php

namespace App\Filament\Admin\Resources\QuranVerses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class QuranVersesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('surah_name')
                    ->label(__('Surah'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('surah_number')
                    ->label(__('Surah #'))
                    ->sortable(),

                TextColumn::make('verse_number')
                    ->label(__('Verse #'))
                    ->sortable(),

                ToggleColumn::make('is_featured')
                    ->label(__('Featured')),
            ])
            ->defaultSort('surah_number')
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
