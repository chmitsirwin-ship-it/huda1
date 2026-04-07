<?php

namespace App\Filament\Admin\Blocks;

use App\Filament\Admin\BlockCategories\Content;
use Filament\Forms\Components\Textarea;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class CustomHtmlBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            Textarea::make('html')
                ->label(__('HTML'))
                ->rows(8),
        ];
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
        return 'components.blocks.custom-html';
    }
}
