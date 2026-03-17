<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class HadithBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('hadith_id')
                ->label(__('Hadith ID'))
                ->numeric()
                ->helperText(__('Leave empty to show a random hadith')),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.hadith';
    }
}
