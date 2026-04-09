<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Pages;

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use App\Models\PrayerTime;
use App\Services\PrayerTimeService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;

class ListPrayerTimes extends ListRecords
{
    protected static string $resource = PrayerTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateMonthly')
                ->label(__('Generate Monthly'))
                ->outlined()
                ->color('success')
                ->schema([
                    Select::make('month')
                        ->label(__('Month'))
                        ->options(array_combine(range(1, 12), array_map(
                            fn ($m) => date('F', mktime(0, 0, 0, $m, 1)),
                            range(1, 12)
                        )))
                        ->default(now()->month)
                        ->required(),

                    Select::make('year')
                        ->label(__('Year'))
                        ->options(array_combine(
                            range(now()->year - 1, now()->year + 2),
                            range(now()->year - 1, now()->year + 2)
                        ))
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data, PrayerTimeService $service): void {
                    $generated = $service->generateMonth((int) $data['year'], (int) $data['month']);

                    Notification::make()
                        ->title(__(':count prayer times generated', ['count' => $generated]))
                        ->success()
                        ->send();
                }),

            Action::make('fillJummahTimes')
                ->label(__('Fill Jummah Times'))
                ->icon('heroicon-o-calendar-days')
                ->outlined()
                ->color('warning')
                ->schema([
                    Grid::make(3)->schema([
                        TimePicker::make('jummah_time')
                            ->label(__('Jummah Time'))
                            ->required(),
                        TimePicker::make('jummah_khutba_time')
                            ->label(__('Jummah Khutba Time')),
                        TimePicker::make('jummah_iqamah')
                            ->label(__('Jummah Iqamah'))
                            ->required(),
                    ])
                ])
                ->action(function (array $data): void {
                    $updated = PrayerTime::query()
                        ->whereRaw('DAYOFWEEK(date) = 6')
                        ->where(function ($query): void {
                            $query->whereNull('jummah_time')
                                ->orWhereNull('jummah_khutba_time')
                                ->orWhereNull('jummah_iqamah');
                        })
                        ->update([
                            'jummah_time' => $data['jummah_time'],
                            'jummah_khutba_time' => $data['jummah_khutba_time'],
                            'jummah_iqamah' => $data['jummah_iqamah'],
                        ]);

                    Notification::make()
                        ->title(__(':count Friday records updated', ['count' => $updated]))
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
