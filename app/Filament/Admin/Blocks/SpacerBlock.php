<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
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

    public static function getView(): ?string
    {
        return 'components.blocks.spacer';
    }
}
