<?php

namespace App\Filament\Admin\Resources\News\Tables;

use App\Support\LocalizedDate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('categories.name')
                    ->label(__('Categories'))
                    ->badge(),
                TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->formatStateUsing(fn ($state) => $state ? LocalizedDate::dateTime($state) : null)
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label(__('Published')),
            ])
            ->defaultSort('published_at', 'desc')
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
