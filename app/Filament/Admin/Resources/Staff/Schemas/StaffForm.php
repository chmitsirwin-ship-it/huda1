<?php

namespace App\Filament\Admin\Resources\Staff\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('photo')
                    ->label(__('Photo'))
                    ->image()
                    ->visibility('public'),

                TranslatableTabs::make()

                    ->schema([
                        TextInput::make('name')->label(__('Name'))->required(),
                        TextInput::make('title')->label(__('Title')),
                        Textarea::make('bio')->label(__('Bio'))->rows(4),
                    ]),

                TextInput::make('email')
                    ->label(__('Email'))
                    ->email(),

                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel(),

                TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }
}
