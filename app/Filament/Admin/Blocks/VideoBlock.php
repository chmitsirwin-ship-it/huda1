<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class VideoBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TextInput::make('video_url')
                ->label(__('Video URL'))
                ->helperText(__('YouTube or Vimeo URL'))
                ->url(),
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('caption')->label(__('Caption')),
                ]),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        if (array_key_exists('caption', $data) && is_array($data['caption'])) {
            $data['caption'] = $data['caption'][$locale] ?? collect($data['caption'])->first(fn ($v) => filled($v)) ?? '';
        }

        return $data;
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
        return 'components.blocks.video';
    }
}
