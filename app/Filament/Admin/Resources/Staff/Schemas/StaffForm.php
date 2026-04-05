<?php

namespace App\Filament\Admin\Resources\Staff\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->schema([
                        FileUpload::make('photo')
                            ->label(__('Photo'))
                            ->image()
                            ->visibility('public')
                            ->columnSpanFull(),
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('name')->label(__('Name'))->required(),
                                TextInput::make('title')->label(__('Title')),
                                Textarea::make('bio')->label(__('Bio'))->rows(4),
                            ]),
                    ]),

                Section::make(__('Contact'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('Phone'))
                            ->tel(),
                    ]),

                Section::make(__('Settings'))
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
