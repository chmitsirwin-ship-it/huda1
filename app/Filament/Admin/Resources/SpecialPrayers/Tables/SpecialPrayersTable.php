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
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class SpecialPrayersTable
{
    public static function configure(Table $table): Table
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', config('app.locale', 'en'));

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query
                        ->where(function (Builder $query) use ($locale, $fallbackLocale, $search): void {
                            $query
                                ->where("name->{$locale}", 'like', "%{$search}%")
                                ->orWhere("name->{$fallbackLocale}", 'like', "%{$search}%");
                        })),

                TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->color('gray')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query
                        ->where(function (Builder $query) use ($locale, $fallbackLocale, $search): void {
                            $query
                                ->where("group->{$locale}", 'like', "%{$search}%")
                                ->orWhere("group->{$fallbackLocale}", 'like', "%{$search}%");
                        })),

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

                TextColumn::make('location.address')
                    ->label(__('Location'))
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_recurring')
                    ->label(__('Recurring'))
                    ->boolean(),
            ])
            ->defaultSort('date', 'asc')
            ->groups([
                Group::make('type')
                    ->label(__('Type'))
                    ->collapsible(),
                Group::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                DateRangeFilter::make('date'),
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options(SpecialPrayerType::class),
                SelectFilter::make('group')
                    ->label(__('Group'))
                    ->options(function () use ($locale, $fallbackLocale): array {
                        return SpecialPrayer::query()
                            ->get()
                            ->pluck('group')
                            ->filter()
                            ->map(fn (mixed $group): ?string => is_array($group)
                                ? ($group[$locale] ?? $group[$fallbackLocale] ?? reset($group) ?: null)
                                : $group)
                            ->filter()
                            ->unique()
                            ->mapWithKeys(fn (string $group): array => [$group => $group])
                            ->all();
                    })
                    ->query(function (Builder $query, array $data) use ($locale, $fallbackLocale): Builder {
                        $value = $data['value'] ?? null;

                        if (blank($value)) {
                            return $query;
                        }

                        return $query->where(function (Builder $query) use ($locale, $fallbackLocale, $value): void {
                            $query
                                ->where("group->{$locale}", $value)
                                ->orWhere("group->{$fallbackLocale}", $value);
                        });
                    }),
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
