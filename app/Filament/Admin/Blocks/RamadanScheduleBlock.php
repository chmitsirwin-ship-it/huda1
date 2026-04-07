<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class RamadanScheduleBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Section Title')),
                    Textarea::make('description')->label(__('Description')),
                ]),
            TextInput::make('hijri_year')
                ->label(__('Hijri Year'))
                ->placeholder('1447'),
            Toggle::make('show_countdown')
                ->label(__('Show Countdown to Iftar'))
                ->default(true),
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'table' => __('Table'),
                    'card' => __('Card'),
                ])
                ->default('table'),
            Repeater::make('schedule')
                ->label(__('Daily Schedule'))
                ->schema([
                    TextInput::make('day')
                        ->label(__('Ramadan Day'))
                        ->numeric()
                        ->required(),
                    DatePicker::make('date')
                        ->label(__('Gregorian Date'))
                        ->required(),
                    TimePicker::make('suhoor')
                        ->label(__('Suhoor'))
                        ->seconds(false)
                        ->required(),
                    TimePicker::make('iftar')
                        ->label(__('Iftar'))
                        ->seconds(false)
                        ->required(),
                    TimePicker::make('taraweeh')
                        ->label(__('Taraweeh'))
                        ->seconds(false),
                ])
                ->columns(5)
                ->defaultItems(1)
                ->collapsible()
                ->collapsed(),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        foreach (['title', 'description'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $data[$field][$locale] ?? collect($data[$field])->first(fn ($v) => filled($v)) ?? '';
            }
        }

        return $data;
    }

    public static function getCategory(): string
    {
        return Worship::class;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }

    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.png');
    }

    public static function getView(): ?string
    {
        return 'components.blocks.ramadan-schedule';
    }
}
