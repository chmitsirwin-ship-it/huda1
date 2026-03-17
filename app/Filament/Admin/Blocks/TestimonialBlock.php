<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class TestimonialBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Section Title')),
                ]),
            Repeater::make('testimonials')
                ->label(__('Testimonials'))
                ->schema([
                    TranslatableTabs::make()
                        ->schema([
                            TextInput::make('name')->label(__('Name')),
                            TextInput::make('role')->label(__('Role')),
                            Textarea::make('quote')->label(__('Quote')),
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

        if (isset($data['testimonials']) && is_array($data['testimonials'])) {
            $data['testimonials'] = array_map(function (array $item) use ($locale): array {
                foreach (['name', 'role', 'quote'] as $field) {
                    if (array_key_exists($field, $item) && is_array($item[$field])) {
                        $item[$field] = $item[$field][$locale] ?? collect($item[$field])->first(fn ($v) => filled($v)) ?? '';
                    }
                }

                return $item;
            }, $data['testimonials']);
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
        return 'components.blocks.testimonial';
    }
}
