<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class ContactMapBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('latitude')
                ->label(__('Latitude'))
                ->numeric(),
            TextInput::make('longitude')
                ->label(__('Longitude'))
                ->numeric(),
            TextInput::make('zoom')
                ->label(__('Zoom Level'))
                ->numeric()
                ->default(15),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.contact-map';
    }
}
