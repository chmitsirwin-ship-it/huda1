<?php

namespace App\Filament\Admin\Resources\Khutbas\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KhutbaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make()

                    ->schema([
                        TextInput::make('title')->label(__('Title'))->required(),
                        TextInput::make('speaker')->label(__('Speaker'))->required(),
                        TextInput::make('topic')->label(__('Topic')),
                        Textarea::make('summary')->label(__('Summary'))->rows(4),
                    ]),

                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),

                TextInput::make('audio_url')
                    ->label(__('Audio URL'))
                    ->url(),

                TextInput::make('video_url')
                    ->label(__('Video URL'))
                    ->url(),

                Toggle::make('is_published')
                    ->label(__('Published')),
            ]);
    }
}
