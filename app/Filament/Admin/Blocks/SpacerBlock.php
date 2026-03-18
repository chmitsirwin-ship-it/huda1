<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class SpacerBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            Select::make('size')
                ->label(__('Size'))
                ->options([
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ])
                ->default('md'),
        ];
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
        return 'components.blocks.spacer';
    }
}
