<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\RichEditor;
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

    public static function getView(): ?string
    {
        return 'components.blocks.rich-text';
    }
}
