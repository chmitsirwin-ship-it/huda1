<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class RichTextBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    RichEditor::make('content')->label(__('Content')),
                ]),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        if (array_key_exists('content', $data) && is_array($data['content'])) {
            $data['content'] = $data['content'][$locale] ?? collect($data['content'])->first(fn ($v) => filled($v)) ?? '';
        }

        return $data;
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
        return 'components.blocks.rich-text';
    }
}
