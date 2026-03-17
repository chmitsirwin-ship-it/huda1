<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class KhutbaArchiveBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('limit')
                ->label(__('Limit'))
                ->numeric()
                ->default(10),
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'list' => __('List'),
                    'grid' => __('Grid'),
                ])
                ->default('list'),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.khutba-archive';
    }
}
