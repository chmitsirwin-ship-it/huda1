<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class CounterBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Section Title')),
                ]),
            Repeater::make('counters')
                ->label(__('Counters'))
                ->schema([
                    TextInput::make('value')->label(__('Value')),
                    TranslatableTabs::make()
                        ->schema([
                            TextInput::make('label')->label(__('Label')),
                        ]),
                ])
                ->defaultItems(1)
                ->collapsible()
                ->collapsed(),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        if (array_key_exists('title', $data) && is_array($data['title'])) {
            $data['title'] = $data['title'][$locale] ?? collect($data['title'])->first(fn ($v) => filled($v)) ?? '';
        }

        if (isset($data['counters']) && is_array($data['counters'])) {
            $data['counters'] = array_map(function (array $item) use ($locale): array {
                if (array_key_exists('label', $item) && is_array($item['label'])) {
                    $item['label'] = $item['label'][$locale] ?? collect($item['label'])->first(fn ($v) => filled($v)) ?? '';
                }

                return $item;
            }, $data['counters']);
        }

        return $data;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        $title = $state['title'] ?? null;
        $label = is_array($title) ? collect($title)->first(fn ($v) => filled($v)) : $title;

        return filled($label) ? static::getBlockName().' - '.$label : parent::getBlockLabel($state, $index);
    }

    public static function getView(): ?string
    {
        return 'components.blocks.counter';
    }
}
