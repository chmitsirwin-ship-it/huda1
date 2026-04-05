<?php

namespace App\Filament\Admin\Resources\Languages\Schemas;

use App\Enums\TextDirection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Language Details'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        TextInput::make('code')
                            ->label(__('Code'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('direction')
                            ->label(__('Direction'))
                            ->options(TextDirection::class),
                    ]),

                Section::make(__('Settings'))
                    ->aside()
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->inline()
                            ->boolean()
                            ->label(__('Active')),
                        ToggleButtons::make('is_default')
                            ->inline()
                            ->boolean()
                            ->label(__('Default')),
                    ]),
            ]);
    }
}
