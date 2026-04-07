<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Content;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class IframeBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('url')
                ->label(__('Iframe URL'))
                ->helperText(__('Only http and https embed URLs are supported.'))
                ->url()
                ->required(),
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Title')),
                ]),
            TextInput::make('height')
                ->label(__('Height'))
                ->numeric()
                ->default(450),
            Select::make('max_width')
                ->label(__('Max Width'))
                ->options([
                    'full' => __('Full Width'),
                    '7xl' => __('7XL'),
                    '6xl' => __('6XL'),
                    '5xl' => __('5XL'),
                    '4xl' => __('4XL'),
                    '3xl' => __('3XL'),
                    '2xl' => __('2XL'),
                ])
                ->default('7xl'),
            Toggle::make('border_radius')
                ->label(__('Rounded Corners'))
                ->default(true),
            Toggle::make('lazy_load')
                ->label(__('Lazy Load'))
                ->default(true),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        if (array_key_exists('title', $data) && is_array($data['title'])) {
            $data['title'] = $data['title'][$locale] ?? collect($data['title'])->first(fn ($value) => filled($value)) ?? '';
        }

        return $data;
    }

    public static function getCategory(): string
    {
        return Content::class;
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
        return 'components.blocks.iframe';
    }
}
