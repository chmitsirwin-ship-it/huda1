<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
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

    public static function getView(): ?string
    {
        return 'components.blocks.gallery';
    }
}
