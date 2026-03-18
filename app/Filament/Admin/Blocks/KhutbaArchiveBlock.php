<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
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
    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }
    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.png');
    }
    public static function getView(): ?string
    {
        return 'components.blocks.khutba-archive';
    }
}
