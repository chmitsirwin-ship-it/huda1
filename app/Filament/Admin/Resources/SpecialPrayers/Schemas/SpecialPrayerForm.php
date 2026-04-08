<?php

namespace App\Filament\Admin\Resources\SpecialPrayers\Schemas;

use App\Enums\SpecialPrayerType;
use App\Models\SpecialPrayer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpecialPrayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->placeholder(__('e.g. Taraweeh, Eid al-Fitr, Tahajjud'))
                    ->required(),

                TextInput::make('group')
                    ->label(__('Group'))
                    ->placeholder(__('e.g. Ramadan 2026, Eid al-Fitr 2026'))
                    ->datalist(fn () => SpecialPrayer::whereNotNull('group')
                        ->distinct()
                        ->pluck('group')
                        ->toArray()),

                Select::make('type')
                    ->label(__('Type'))
                    ->options(SpecialPrayerType::class)
                    ->required()
                    ->default('other'),

                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),

                TimePicker::make('time')
                    ->label(__('Start Time'))
                    ->seconds(false)
                    ->required(),

                TimePicker::make('end_time')
                    ->label(__('End Time'))
                    ->seconds(false),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(2),

                Toggle::make('is_recurring')
                    ->label(__('Recurring'))
                    ->helperText(__('Mark as recurring for daily prayers like Taraweeh')),
            ]);
    }
}
