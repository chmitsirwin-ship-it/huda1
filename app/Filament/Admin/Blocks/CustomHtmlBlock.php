<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Textarea;
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

    public static function getView(): ?string
    {
        return 'components.blocks.custom-html';
    }
}
