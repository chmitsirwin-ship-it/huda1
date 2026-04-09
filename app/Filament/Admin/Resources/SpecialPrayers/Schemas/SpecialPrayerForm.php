<?php

namespace App\Filament\Admin\Resources\SpecialPrayers\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\SpecialPrayerType;
use App\Models\SpecialPrayer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use SalemAljebaly\FilamentMapPicker\MapPicker;

class SpecialPrayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TranslatableTabs::make()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Name'))
                                ->placeholder(__('e.g. Taraweeh, Eid al-Fitr, Tahajjud'))
                                ->required(),

                            TextInput::make('group')
                                ->label(__('Group'))
                                ->placeholder(__('e.g. Ramadan 2026, Eid al-Fitr 2026'))
                                ->datalist(function (): array {
                                    $locale = app()->getLocale();
                                    $fallbackLocale = config('app.fallback_locale', config('app.locale', 'en'));

                                    return SpecialPrayer::query()
                                        ->get()
                                        ->pluck('group')
                                        ->filter()
                                        ->map(fn (mixed $group): ?string => is_array($group)
                                            ? ($group[$locale] ?? $group[$fallbackLocale] ?? reset($group) ?: null)
                                            : $group)
                                        ->filter()
                                        ->unique()
                                        ->values()
                                        ->all();
                                }),

                            Textarea::make('description')
                                ->columnSpanFull()
                                ->label(__('Description'))
                                ->rows(2),
                        ])->columnSpanFull(),

                    ToggleButtons::make('type')
                        ->label(__('Type'))
                        ->options(SpecialPrayerType::class)
                        ->inline()
                        ->required()
                        ->default('other'),

                    Grid::make(3)->schema([
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
                    ])->columnSpanFull(),

                    ToggleButtons::make('is_recurring')
                        ->inline()->boolean()
                        ->label(__('Recurring'))
                        ->helperText(__('Mark as recurring for daily prayers like Taraweeh')),
                    Hidden::make('location.latitude'),
                    Hidden::make('location.longitude'),
                    TextInput::make('location.address')
                        ->label(__('Address'))
                        ->placeholder(__('Optional location label'))
                        ->columnSpanFull(),
                    MapPicker::make('location.map')
                        ->hiddenLabel()
                        ->label(__('Location'))
                        ->latlngFields('location.latitude', 'location.longitude')
                        ->searchable()
                        ->columnSpanFull()
                        ->collapsibleSearch()
                        ->draggable()
                        ->height(320)
                        ->dehydrated(false),
                ])->columns(2),
            ]);
    }
}
