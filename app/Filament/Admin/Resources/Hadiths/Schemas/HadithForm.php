<?php

namespace App\Filament\Admin\Resources\Hadiths\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HadithForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Hadith Content'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('narrator')->label(__('Narrator')),
                                Textarea::make('text')->label(__('Text'))->rows(5),
                                TextInput::make('source')->label(__('Source')),
                                TextInput::make('grade')->label(__('Grade')),
                            ]),
                    ]),

                Section::make(__('Settings'))
                    ->aside()
                    ->schema([
                        TextInput::make('collection')
                            ->label(__('Collection')),
                        Toggle::make('is_featured')
                            ->label(__('Featured')),
                    ]),
            ]);
    }
}
