<?php

namespace App\Filament\Admin\Resources\QuranVerses\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuranVerseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Verse Reference'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('surah_number')
                            ->label(__('Surah Number'))
                            ->numeric()
                            ->required(),
                        TextInput::make('verse_number')
                            ->label(__('Verse Number'))
                            ->numeric()
                            ->required(),
                        TextInput::make('surah_name')
                            ->label(__('Surah Name'))
                            ->required(),
                    ]),

                Section::make(__('Text'))
                    ->schema([
                        Textarea::make('arabic_text')
                            ->label(__('Arabic Text'))
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        TranslatableTabs::make()
                            ->schema([
                                Textarea::make('translation')->label(__('Translation'))->required()->rows(4),
                                Textarea::make('tafsir')->label(__('Tafsir'))->rows(4),
                            ]),
                    ]),

                Section::make(__('Settings'))
                    ->aside()
                    ->schema([
                        Toggle::make('is_featured')
                            ->label(__('Featured')),
                    ]),
            ]);
    }
}
