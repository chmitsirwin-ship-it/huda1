<?php

namespace App\Filament\Admin\Resources\KhutbaCategories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                        TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                    ]),
            ]);
    }
}
