<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;

class NewsAudioBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'news_audio';
    }

    public static function getLabel(): string
    {
        return __('News Audio');
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading(__('Insert Audio'))
            ->schema([
                FileUpload::make('audio')
                    ->label(__('Audio File'))
                    ->disk('public')
                    ->directory('news/rich-content/audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav', 'audio/ogg', 'audio/webm'])
                    ->required(),
            ]);
    }

    public static function toPreviewHtml(array $config): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.news-audio.preview', [
            'audio' => $config['audio'] ?? null,
        ])->render();
    }

    public static function toHtml(array $config, array $data): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.news-audio.index', [
            'audio' => $config['audio'] ?? null,
        ])->render();
    }
}
