<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class EventsBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'grid' => __('Grid'),
                    'list' => __('List'),
                ])
                ->default('grid'),
            TextInput::make('limit')
                ->label(__('Limit'))
                ->numeric()
                ->default(6),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.events';
    }
}
