<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class PrayerTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->description(fn($record) => Carbon::parse($record->date)->translatedFormat('l'),'above')
                    ->date()
                    ->sortable(),

                TextColumn::make('fajr_adhan')
                    ->description(function ($record) {
                        return $record->fajr_iqamah ? __('Iqamah').': ' . Carbon::parse($record->fajr_iqamah)->translatedFormat('g:i A') : null;
                    })
                    ->time()
                    ->label(__('Fajr')),

                TextColumn::make('dhuhr_adhan')
                     ->description(function ($record) {
                        return $record->dhuhr_iqamah ? __('Iqamah').': ' . Carbon::parse($record->dhuhr_iqamah)->translatedFormat('g:i A') : null;
                    })
                    ->time()
                    ->label(__('Dhuhr')),

                TextColumn::make('asr_adhan')
                     ->description(function ($record) {
                        return $record->asr_iqamah ? __('Iqamah').': ' . Carbon::parse($record->asr_iqamah)->translatedFormat('g:i A') : null;
                    })
                    ->time()
                    ->label(__('Asr')),

                TextColumn::make('maghrib_adhan')
                     ->description(function ($record) {
                        return $record->maghrib_iqamah ? __('Iqamah').': ' . Carbon::parse($record->maghrib_iqamah)->translatedFormat('g:i A') : null;
                    })
                    ->time()
                    ->label(__('Maghrib')),

                TextColumn::make('isha_adhan')
                     ->description(function ($record) {
                        return $record->isha_iqamah ? __('Iqamah').': ' . Carbon::parse($record->isha_iqamah)->translatedFormat('g:i A') : null;
                    })
                    ->time()
                    ->label(__('Isha')),
            ])
            ->defaultSort('date', 'desc')
            ->filters([])
            ->recordActions([
                Action::make('adjust_iqamah')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->schema([
                        TextInput::make('adjustment')
                            ->label(__('Adjustment (in minutes)'))
                            ->hint(__('Will be added above adhan time'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->action(function ($record, array $data) {
                        $adjustment = (int)$data['adjustment'];
                        $prayers = [
                            'fajr',
                            'dhuhr',
                            'asr',
                            'maghrib',
                            'isha',
                        ];
                        foreach ($prayers as $prayer) {
                            $adhanField = "{$prayer}_adhan";
                            $iqamahField = "{$prayer}_iqamah";

                            if ($record->$adhanField) {
                                $record->$iqamahField = Carbon::parse($record->$adhanField)
                                    ->addMinutes($adjustment)
                                    ->format('H:i:s');
                            }
                        }

                        $record->save();
                        Notification::make()
                            ->title(__('Iqamah times adjusted successfully!'))
                            ->success()
                            ->send();
                    })
                    ->label(__('Adjust Iqamah Times')),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                    DeleteBulkAction::make(),
                    BulkAction::make('adjust_iqamah')
                        ->color('success')
                        ->button()
                        ->schema([
                            TextInput::make('adjustment')
                                ->label(__('Adjustment (in minutes)'))
                                ->hint(__('Will be added above adhan time'))
                                ->numeric()
                                ->default(0),
                        ])
                        ->action(function ($records, array $data) {

                            $adjustment = (int) $data['adjustment'];

                            $prayers = [
                                'fajr',
                                'dhuhr',
                                'asr',
                                'maghrib',
                                'isha',
                            ];

                            foreach ($records as $record) {

                                foreach ($prayers as $prayer) {
                                    $adhanField = "{$prayer}_adhan";
                                    $iqamahField = "{$prayer}_iqamah";

                                    if ($record->$adhanField) {
                                        $record->$iqamahField = Carbon::parse($record->$adhanField)
                                            ->addMinutes($adjustment)
                                            ->format('H:i:s');
                                    }
                                }

                                $record->save();
                            }

                            Notification::make()
                                ->title(__('Iqamah times adjusted successfully!'))
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->label(__('Adjust Iqamah Times'))

            ]);
    }
}
