<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Pages;

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use App\Services\PrayerTimeService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPrayerTimes extends ListRecords
{
    protected static string $resource = PrayerTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateMonthly')
                ->label(__('Generate Monthly'))
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

            CreateAction::make(),
        ];
    }
}
