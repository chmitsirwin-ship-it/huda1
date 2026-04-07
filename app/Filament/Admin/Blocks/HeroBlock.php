<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Layout;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class HeroBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('heading')->label(__('Heading')),
                    TextInput::make('subheading')->label(__('Subheading')),
                    TextInput::make('button_text')->label(__('Button Text')),
                ]),
            TextInput::make('button_url')->label(__('Button URL')),
            FileUpload::make('background_image')
                ->label(__('Background Image'))
                ->image()
                ->visibility('public'),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        foreach (['heading', 'subheading', 'button_text'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $data[$field][$locale] ?? collect($data[$field])->first(fn ($v) => filled($v)) ?? '';
            }
        }

        return $data;
    }

    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.png');
    }

    public static function getCategory(): string
    {
        return Layout::class;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }

    public static function getView(): ?string
    {
        return 'components.blocks.hero';
    }
}
