<?php

namespace App\Filament\Admin\Resources\Languages\Schemas;

use App\Enums\TextDirection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label(__('Active')),

                Toggle::make('is_default')
                    ->label(__('Default')),
            ]);
    }
}
