<?php

namespace App\Filament\Admin\Blocks;

use App\Filament\Admin\BlockCategories\Contact;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class ContactMapBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('latitude')
                ->label(__('Latitude'))
                ->numeric(),
            TextInput::make('longitude')
                ->label(__('Longitude'))
                ->numeric(),
            TextInput::make('zoom')
                ->label(__('Zoom Level'))
                ->numeric()
                ->default(15),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.contact-map';
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
