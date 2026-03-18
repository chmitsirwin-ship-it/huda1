<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrayerTimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required()
                    ->unique(ignoreRecord: true),

                Section::make(__('Fajr'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('fajr_adhan')
                            ->label(__('Fajr Adhan')),
                        TimePicker::make('fajr_iqamah')
                            ->label(__('Fajr Iqamah')),
                    ]),

                Section::make(__('Sunrise'))
                    ->collapsible()
                    ->schema([
                        TimePicker::make('sunrise')
                            ->label(__('Sunrise')),
                    ]),

                Section::make(__('Dhuhr'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('dhuhr_adhan')
                            ->label(__('Dhuhr Adhan')),
                        TimePicker::make('dhuhr_iqamah')
                            ->label(__('Dhuhr Iqamah')),
                    ]),

                Section::make(__('Asr'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('asr_adhan')
                            ->label(__('Asr Adhan')),
                        TimePicker::make('asr_iqamah')
                            ->label(__('Asr Iqamah')),
                    ]),

                Section::make(__('Maghrib'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('maghrib_adhan')
                            ->label(__('Maghrib Adhan')),
                        TimePicker::make('maghrib_iqamah')
                            ->label(__('Maghrib Iqamah')),
                    ]),

                Section::make(__('Isha'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('isha_adhan')
                            ->label(__('Isha Adhan')),
                        TimePicker::make('isha_iqamah')
                            ->label(__('Isha Iqamah')),
                    ]),

                Section::make(__('Jummah'))
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        TimePicker::make('jummah_time')
                            ->label(__('Jummah Time')),
                        TimePicker::make('jummah_khutba_time')
                            ->label(__('Jummah Khutba Time')),
                    ]),
            ]);
    }
}
