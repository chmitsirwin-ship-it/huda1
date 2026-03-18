<?php

namespace App\Filament\Admin\Resources\MediaItems\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\MediaType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Media'))
                    ->schema([
                        FileUpload::make('file_path')
                            ->label(__('File'))
                            ->image()
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options(MediaType::class)
                            ->default(MediaType::Image)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title')),
                                TextInput::make('alt_text')->label(__('Alt Text')),
                            ]),
                        TextInput::make('collection')
                            ->label(__('Collection'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Settings'))
                    ->aside()
                    ->schema([
                        TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
