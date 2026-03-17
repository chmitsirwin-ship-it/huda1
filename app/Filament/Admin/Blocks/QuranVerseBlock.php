<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class QuranVerseBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('verse_id')
                ->label(__('Verse ID'))
                ->numeric()
                ->helperText(__('Leave empty to show a random verse')),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.quran-verse';
    }
}
