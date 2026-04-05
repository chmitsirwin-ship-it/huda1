<?php

namespace App\Filament\Admin\Resources\KhutbaCategories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KhutbaCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Category Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('name')->label(__('Name'))->required(),
                            ]),
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
