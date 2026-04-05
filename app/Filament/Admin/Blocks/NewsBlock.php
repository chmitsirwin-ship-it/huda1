<?php

namespace App\Filament\Admin\Blocks;

use App\Models\NewsCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class NewsBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('heading')
                ->label(__('Heading'))
                ->default(__('Latest News')),
            Select::make('category_ids')
                ->label(__('Categories'))
                ->options(fn (): array => NewsCategory::query()
                    ->active()
                    ->get()
                    ->mapWithKeys(fn (NewsCategory $category): array => [$category->id => (string) $category->name])
                    ->all())
                ->multiple()
                ->searchable(),
            TextInput::make('limit')
                ->label(__('Limit'))
                ->numeric()
                ->default(6),
            Select::make('style')
                ->label(__('Style'))
                ->options([
                    'grid' => __('Grid'),
                    'list' => __('List'),
                ])
                ->default('grid'),
            TextInput::make('button_text')
                ->label(__('Button Text'))
                ->default(__('View All News')),
            TextInput::make('button_url')
                ->label(__('Button URL'))
                ->default('/news'),
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
        return 'components.blocks.news';
    }
}
