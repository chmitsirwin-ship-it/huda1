<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\AnnouncementType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Announcement Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title'))->required(),
                                RichEditor::make('content')->label(__('Content'))->columnSpanFull(),
                            ]),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options(AnnouncementType::class)
                            ->default(AnnouncementType::General)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Schedule'))
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label(__('Published At')),
                        DateTimePicker::make('expires_at')
                            ->label(__('Expires At')),
                    ]),

                Section::make(__('Status'))
                    ->aside()
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->inline()
                            ->boolean()
                            ->label(__('Active'))
                            ->default(true),
                    ]),
            ]);
    }
}
