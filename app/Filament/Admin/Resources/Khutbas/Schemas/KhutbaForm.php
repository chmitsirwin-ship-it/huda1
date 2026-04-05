<?php

namespace App\Filament\Admin\Resources\Khutbas\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Models\KhutbaCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KhutbaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Khutba Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title'))->required(),
                                TextInput::make('speaker')->label(__('Speaker'))->required(),
                                TextInput::make('topic')->label(__('Topic')),
                                Textarea::make('summary')->label(__('Summary'))->rows(4),
                                Textarea::make('content')->label(__('Content'))->rows(14)->columnSpanFull(),
                            ]),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->unique(ignoreRecord: true),
                        DatePicker::make('date')
                            ->label(__('Date'))
                            ->required()
                            ->columnSpanFull(),
                        Select::make('categories')
                            ->label(__('Categories'))
                            ->relationship(name: 'categories', titleAttribute: 'id', modifyQueryUsing: fn ($query) => $query->active())
                            ->getOptionLabelFromRecordUsing(fn (KhutbaCategory $record): string => (string) $record->name)
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Media'))
                    ->columns(2)
                    ->schema([
                        FileUpload::make('audio_url')
                            ->label(__('Audio File'))
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/mp4', 'audio/x-m4a', 'audio/wav', 'audio/ogg'])
                            ->directory('khutbas/audio'),
                        FileUpload::make('video_url')
                            ->label(__('Video File'))
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm', 'video/x-msvideo', 'video/x-matroska'])
                            ->directory('khutbas/video'),
                        FileUpload::make('featured_image')
                            ->label(__('Featured Image'))
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Status'))
                    ->aside()
                    ->schema([
                        Toggle::make('is_published')
                            ->label(__('Published')),
                    ]),
            ]);
    }
}
