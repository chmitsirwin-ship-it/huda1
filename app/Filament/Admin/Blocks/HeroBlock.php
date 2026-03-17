<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
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

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        $heading = $state['heading'] ?? null;
        $label = is_array($heading) ? collect($heading)->first(fn ($v) => filled($v)) : $heading;

        return filled($label) ? static::getBlockName().' - '.$label : parent::getBlockLabel($state, $index);
    }

    public static function getView(): ?string
    {
        return 'components.blocks.hero';
    }
}
