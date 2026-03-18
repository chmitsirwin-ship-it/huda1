<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class FaqBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Section Title')),
                ]),
            Repeater::make('items')
                ->label(__('FAQ Items'))
                ->schema([
                    TranslatableTabs::make()
                        ->schema([
                            TextInput::make('question')->label(__('Question')),
                            Textarea::make('answer')->label(__('Answer')),
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

        if (isset($data['items']) && is_array($data['items'])) {
            $data['items'] = array_map(function (array $item) use ($locale): array {
                foreach (['question', 'answer'] as $field) {
                    if (array_key_exists($field, $item) && is_array($item[$field])) {
                        $item[$field] = $item[$field][$locale] ?? collect($item[$field])->first(fn ($v) => filled($v)) ?? '';
                    }
                }

                return $item;
            }, $data['items']);
        }

        return $data;
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
        return 'components.blocks.faq';
    }
}
