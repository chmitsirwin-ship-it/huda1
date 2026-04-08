<?php

namespace App\Filament\Admin\Blocks;

use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class SpecialPrayersBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('Section Title'))
                ->default(__('Special Prayers')),

            TextInput::make('limit')
                ->label(__('Number of prayers to show'))
                ->numeric()
                ->default(10),
        ];
    }

    public static function getCategory(): string
    {
        return Worship::class;
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
        return 'components.blocks.special-prayers';
    }
}
