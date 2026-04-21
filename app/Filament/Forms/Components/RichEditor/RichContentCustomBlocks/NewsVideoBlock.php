<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;

class NewsVideoBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'news_video';
    }

    public static function getLabel(): string
    {
        return __('News Video');
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading(__('Insert Video'))
            ->schema([
                FileUpload::make('video')
                    ->label(__('Video File'))
                    ->disk('public')
                    ->directory('news/rich-content/video')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-m4v'])
                    ->required(),
            ]);
    }

    public static function toPreviewHtml(array $config): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.news-video.preview', [
            'video' => $config['video'] ?? null,
        ])->render();
    }

    public static function toHtml(array $config, array $data): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.news-video.index', [
            'video' => $config['video'] ?? null,
        ])->render();
    }
}
