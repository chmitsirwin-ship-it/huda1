<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Contact;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class ContactFormBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Title')),
                    Textarea::make('description')->label(__('Description')),
                ]),
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

    public static function getView(): ?string
    {
        return 'components.blocks.contact-form';
    }

    public static function getCategory(): string
    {
        return Contact::class;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }

    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.png');
    }
}
