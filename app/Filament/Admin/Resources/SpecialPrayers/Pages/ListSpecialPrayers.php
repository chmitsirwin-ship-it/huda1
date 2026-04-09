<?php

namespace App\Filament\Admin\Resources\SpecialPrayers\Pages;

use App\Enums\SpecialPrayerType;
use App\Filament\Admin\Resources\SpecialPrayers\SpecialPrayerResource;
use App\Models\PrayerTime;
use App\Models\SpecialPrayer;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListSpecialPrayers extends ListRecords
{
    protected static string $resource = SpecialPrayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bulkGenerate')
                ->label(__('Bulk Generate'))
                ->schema([
                    TextInput::make('group')
                        ->label(__('Group Name'))
                        ->placeholder(__('e.g. Ramadan 2026'))
                        ->required(),

                    Select::make('type')
                        ->label(__('Type'))
                        ->options(SpecialPrayerType::class)
                        ->default('ramadan')
                        ->required(),

                    Select::make('month')
                        ->label(__('Month'))
                        ->options(array_combine(range(1, 12), array_map(
                            fn ($m) => date('F', mktime(0, 0, 0, $m, 1)),
                            range(1, 12)
                        )))
                        ->required(),

                    Select::make('year')
                        ->label(__('Year'))
                        ->options(array_combine(
                            range(now()->year - 1, now()->year + 2),
                            range(now()->year - 1, now()->year + 2)
                        ))
                        ->default(now()->year)
                        ->required(),

                    Repeater::make('prayers')
                        ->label(__('Prayers to Generate'))
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Prayer Name'))
                                ->placeholder(__('e.g. Taraweeh, Tahajjud, Qiyam'))
                                ->required(),
                            Select::make('base_on')
                                ->label(__('Base Time On'))
                                ->options([
                                    'isha' => __('Isha Adhan'),
                                    'maghrib' => __('Maghrib Adhan'),
                                    'fajr' => __('Fajr Adhan'),
                                    'fixed' => __('Fixed Time'),
                                ])
                                ->default('isha')
                                ->required()
                                ->live(),
                            TextInput::make('offset')
                                ->label(__('Offset (minutes)'))
                                ->numeric()
                                ->default(30)
                                ->visible(fn ($get) => $get('base_on') !== 'fixed'),
                            TimePicker::make('fixed_time')
                                ->label(__('Fixed Time'))
                                ->seconds(false)
                                ->visible(fn ($get) => $get('base_on') === 'fixed'),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->addActionLabel(__('Add Prayer')),
                ])
                ->action(function (array $data): void {
                    $locale = config('app.fallback_locale', config('app.locale', 'en'));
                    $year = (int) $data['year'];
                    $month = (int) $data['month'];
                    $group = $data['group'];
                    $type = $data['type'];
                    $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
                    $generated = 0;

                    foreach ($data['prayers'] as $prayerConfig) {
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $date = Carbon::createFromDate($year, $month, $day);

                            $time = null;
                            if ($prayerConfig['base_on'] === 'fixed') {
                                $time = $prayerConfig['fixed_time'];
                            } else {
                                $prayerTime = PrayerTime::where('date', $date->toDateString())->first();
                                $adhanField = $prayerConfig['base_on'].'_adhan';
                                $baseTime = $prayerTime?->$adhanField;
                                $offset = (int) ($prayerConfig['offset'] ?? 0);

                                if ($baseTime) {
                                    $time = Carbon::parse($baseTime)
                                        ->addMinutes($offset)
                                        ->format('H:i');
                                }
                            }

                            $specialPrayerQuery = SpecialPrayer::query()
                                ->where("name->{$locale}", $prayerConfig['name'])
                                ->whereDate('date', $date->toDateString());

                            if (filled($group)) {
                                $specialPrayerQuery->where("group->{$locale}", $group);
                            } else {
                                $specialPrayerQuery->whereNull('group');
                            }

                            $specialPrayer = $specialPrayerQuery->first() ?? new SpecialPrayer;
                            $specialPrayer->setTranslation('name', $locale, $prayerConfig['name']);
                            $specialPrayer->date = $date->toDateString();
                            $specialPrayer->time = $time ?? '21:00';
                            $specialPrayer->type = $type;
                            $specialPrayer->is_recurring = true;

                            if (filled($group)) {
                                $specialPrayer->setTranslation('group', $locale, $group);
                            } else {
                                $specialPrayer->group = null;
                            }

                            $specialPrayer->save();

                            $generated++;
                        }
                    }

                    Notification::make()
                        ->title(__(':count special prayers generated', ['count' => $generated]))
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
