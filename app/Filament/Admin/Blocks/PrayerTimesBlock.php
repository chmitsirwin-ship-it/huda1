<?php

namespace App\Filament\Admin\Blocks;

use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
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
            Toggle::make('show_jummah')
                ->label(__('Always Show Next Jummah'))
                ->helperText(__('Display the next upcoming Friday Jummah times at the bottom of this block.'))
                ->default(true),
            Toggle::make('show_next_salah_countdown')
                ->label(__('Show Next Salah Countdown'))
                ->helperText(__('Display a live countdown to the upcoming salah time above the Jummah section.'))
                ->default(true),
        ];
    }

    public static function getCategory(): string
    {
        return Worship::class;
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
        return 'components.blocks.prayer-times';
    }
}
