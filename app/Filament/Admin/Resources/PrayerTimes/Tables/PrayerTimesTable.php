<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Tables;

use App\Support\LocalizedDate;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PrayerTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->description(fn ($record) => LocalizedDate::weekday($record->date), 'above')
                    ->formatStateUsing(fn ($state) => LocalizedDate::date($state))
                    ->sortable(),

                TextColumn::make('fajr_adhan')
                    ->description(function ($record) {
                        return $record->fajr_iqamah ? __('Iqamah').': '.LocalizedDate::time($record->fajr_iqamah) : null;
                    })
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state))
                    ->label(__('Fajr')),

                TextColumn::make('dhuhr_adhan')
                    ->description(function ($record) {
                        return $record->dhuhr_iqamah ? __('Iqamah').': '.LocalizedDate::time($record->dhuhr_iqamah) : null;
                    })
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state))
                    ->label(__('Dhuhr')),

                TextColumn::make('asr_adhan')
                    ->description(function ($record) {
                        return $record->asr_iqamah ? __('Iqamah').': '.LocalizedDate::time($record->asr_iqamah) : null;
                    })
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state))
                    ->label(__('Asr')),

                TextColumn::make('maghrib_adhan')
                    ->description(function ($record) {
                        return $record->maghrib_iqamah ? __('Iqamah').': '.LocalizedDate::time($record->maghrib_iqamah) : null;
                    })
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state))
                    ->label(__('Maghrib')),

                TextColumn::make('isha_adhan')
                    ->description(function ($record) {
                        return $record->isha_iqamah ? __('Iqamah').': '.LocalizedDate::time($record->isha_iqamah) : null;
                    })
                    ->formatStateUsing(fn ($state) => LocalizedDate::time($state))
                    ->label(__('Isha')),
            ])
            ->defaultSort('date', 'asc')
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label(__('From'))
                            ->default(today()),
                        DatePicker::make('date_to')
                            ->label(__('To')),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['date_from'], fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
                        ->when($data['date_to'], fn (Builder $q, $date) => $q->whereDate('date', '<=', $date))
                    )
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = __('From').': '.Carbon::parse($data['date_from'])->toFormattedDateString();
                        }
                        if ($data['date_to'] ?? null) {
                            $indicators[] = __('To').': '.Carbon::parse($data['date_to'])->toFormattedDateString();
                        }

                        return $indicators;
                    })
                    ->default(),
            ])
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
                        $adjustment = (int) $data['adjustment'];
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
                    ->label(__('Adjust Iqamah Times')),

            ]);
    }
}
