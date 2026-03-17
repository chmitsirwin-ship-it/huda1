<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrayerTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('fajr_adhan')
                    ->label(__('Fajr')),

                TextColumn::make('dhuhr_adhan')
                    ->label(__('Dhuhr')),

                TextColumn::make('asr_adhan')
                    ->label(__('Asr')),

                TextColumn::make('maghrib_adhan')
                    ->label(__('Maghrib')),

                TextColumn::make('isha_adhan')
                    ->label(__('Isha')),
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
