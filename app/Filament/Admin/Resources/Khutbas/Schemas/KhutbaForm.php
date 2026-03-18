<?php

namespace App\Filament\Admin\Resources\Khutbas\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
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
                            ]),
                        DatePicker::make('date')
                            ->label(__('Date'))
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Media'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('audio_url')
                            ->label(__('Audio URL'))
                            ->url(),
                        TextInput::make('video_url')
                            ->label(__('Video URL'))
                            ->url(),
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
