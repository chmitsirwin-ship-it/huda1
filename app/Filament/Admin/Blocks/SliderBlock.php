<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    public static function getView(): ?string
    {
        return 'components.blocks.slider';
    }
}
