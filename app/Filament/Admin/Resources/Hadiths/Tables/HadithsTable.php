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
                    ->searchable(),

                TextColumn::make('source')
                    ->label(__('Source')),

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
