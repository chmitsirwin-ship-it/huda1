<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class AnnouncementsBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('limit')
                ->label(__('Limit'))
                ->numeric()
                ->default(5),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.announcements';
    }
}
