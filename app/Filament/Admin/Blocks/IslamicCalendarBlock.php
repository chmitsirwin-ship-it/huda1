<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class IslamicCalendarBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Section Title')),
                ]),
            Repeater::make('events')
                ->label(__('Calendar Events'))
                ->schema([
                    TranslatableTabs::make()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Event Name'))
                                ->required(),
                            Textarea::make('description')->label(__('Description')),
                        ]),
                    DatePicker::make('gregorian_date')
                        ->label(__('Gregorian Date'))
                        ->required(),
                    Select::make('icon')
                        ->label(__('Icon'))
                        ->options([
                            'moon' => __('Moon'),
                            'star' => __('Star'),
                            'mosque' => __('Mosque'),
                            'book' => __('Book'),
                            'calendar' => __('Calendar'),
                        ])
                        ->default('calendar'),
                    Toggle::make('highlight')
                        ->label(__('Highlight'))
                        ->default(false),
                ])
                ->defaultItems(1)
                ->collapsible()
                ->collapsed(),
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'timeline' => __('Timeline'),
                    'list' => __('List'),
                    'card' => __('Card'),
                ])
                ->default('timeline'),
            Toggle::make('show_past')
                ->label(__('Show Past Events'))
                ->default(true),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        if (array_key_exists('title', $data) && is_array($data['title'])) {
            $data['title'] = $data['title'][$locale] ?? collect($data['title'])->first(fn ($value) => filled($value)) ?? '';
        }

        if (isset($data['events']) && is_array($data['events'])) {
            $data['events'] = array_map(function (array $item) use ($locale): array {
                foreach (['name', 'description'] as $field) {
                    if (array_key_exists($field, $item) && is_array($item[$field])) {
                        $item[$field] = $item[$field][$locale] ?? collect($item[$field])->first(fn ($value) => filled($value)) ?? '';
                    }
                }

                return $item;
            }, $data['events']);
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
        return 'components.blocks.islamic-calendar';
    }
}
