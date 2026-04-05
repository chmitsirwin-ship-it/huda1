<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
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
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('heading')->label(__('Heading'))
                ]),
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
        ];
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }
    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        foreach (['heading'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $data[$field][$locale] ?? collect($data[$field])->first(fn ($v) => filled($v)) ?? '';
            }
        }

        return $data;
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
