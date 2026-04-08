<?php

namespace App\Filament\Admin\Resources\SpecialPrayers\Tables;

use App\Enums\SpecialPrayerType;
use App\Models\SpecialPrayer;
use App\Support\LocalizedDate;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SpecialPrayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),

                TextColumn::make('date')
                    ->label(__('Date'))
                    ->formatStateUsing(fn ($state) => LocalizedDate::date($state))
                    ->description(fn ($record) => LocalizedDate::weekday($record->date), 'above')
                    ->sortable(),

                TextColumn::make('time')
                    ->label(__('Start Time'))
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state)),

                TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->formatStateUsing(fn ($state) => $state ? LocalizedDate::time($state) : '-'),

                IconColumn::make('is_recurring')
                    ->label(__('Recurring'))
                    ->boolean(),
            ])
            ->defaultSort('date', 'asc')
            ->defaultGroup(
                Group::make('group')
                    ->label(__('Group'))
                    ->collapsible()
            )
            ->groups([
                Group::make('group')
                    ->label(__('Group'))
                    ->collapsible(),
                Group::make('type')
                    ->label(__('Type'))
                    ->collapsible(),
                Group::make('name')
                    ->label(__('Name'))
                    ->collapsible(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options(SpecialPrayerType::class),
                SelectFilter::make('group')
                    ->label(__('Group'))
                    ->options(fn () => SpecialPrayer::whereNotNull('group')
                        ->distinct()
                        ->pluck('group', 'group')
                        ->toArray()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
