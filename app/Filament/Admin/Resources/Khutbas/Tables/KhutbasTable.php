<?php

namespace App\Filament\Admin\Resources\Khutbas\Tables;

use App\Support\LocalizedDate;
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
                    ->searchable(),

                TextColumn::make('speaker')
                    ->label(__('Speaker')),

                TextColumn::make('date')
                    ->label(__('Date'))
                    ->formatStateUsing(fn ($state) => LocalizedDate::date($state))
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
