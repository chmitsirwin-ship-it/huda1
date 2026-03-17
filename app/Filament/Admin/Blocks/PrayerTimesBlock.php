<?php

namespace App\Filament\Admin\Blocks;

use Filament\Forms\Components\Select;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class PrayerTimesBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'compact' => __('Compact'),
                    'detailed' => __('Detailed'),
                    'card' => __('Card'),
                ])
                ->default('compact'),
        ];
    }

    public static function getView(): ?string
    {
        return 'components.blocks.prayer-times';
    }
}
