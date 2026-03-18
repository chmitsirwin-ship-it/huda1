<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
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
    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }
    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.basename(self::class).'.jpg');
    }
    public static function getView(): ?string
    {
        return 'components.blocks.hadith';
    }
}
