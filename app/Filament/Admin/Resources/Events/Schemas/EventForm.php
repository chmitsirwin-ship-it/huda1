<?php

namespace App\Filament\Admin\Resources\Events\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\EventStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Event Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title'))->required(),
                                RichEditor::make('description')->label(__('Description'))->columnSpanFull(),
                                TextInput::make('location')->label(__('Location')),
                            ]),
                        FileUpload::make('image')
                            ->label(__('Image'))
                            ->image()
                            ->visibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Schedule'))
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label(__('Starts At'))
                            ->required(),
                        DateTimePicker::make('ends_at')
                            ->label(__('Ends At')),
                    ]),

                Section::make(__('Status'))
                    ->aside()
                    ->schema([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(EventStatus::class)
                            ->default(EventStatus::Draft)
                            ->required(),
                        Toggle::make('is_featured')
                            ->label(__('Featured')),
                    ]),
            ]);
    }
}
