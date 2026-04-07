<?php

namespace App\Filament\Admin\Blocks;

use App\Filament\Admin\BlockCategories\Media;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class GalleryBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('collection')
                ->label(__('Collection'))
                ->helperText(__('Filter by collection name, leave empty to show all')),
            TextInput::make('limit')
                ->label(__('Limit'))
                ->numeric()
                ->default(12),
        ];
    }

    public static function getCategory(): string
    {
        return Media::class;
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
        return 'components.blocks.gallery';
    }
}
