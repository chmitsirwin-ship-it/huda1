<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class SliderBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('limit')
                ->label(__('Max Slides'))
                ->numeric()
                ->default(5),
            Select::make('autoplay')
                ->label(__('Autoplay'))
                ->options(['1' => __('Enabled'), '0' => __('Disabled')])
                ->default('1'),
        ];
    }
    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }
    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.basename(self::class).'.jpg');
    }
    public static function getView(): ?string
    {
        return 'components.blocks.slider';
    }
}
