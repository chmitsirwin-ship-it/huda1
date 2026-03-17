<?php

namespace App\Filament\Admin\Resources\Announcements\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\AnnouncementType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make()

                    ->schema([
                        TextInput::make('title')->label(__('Title'))->required(),
                        RichEditor::make('content')->label(__('Content')),
                    ]),

                Select::make('type')
                    ->label(__('Type'))
                    ->options(AnnouncementType::class)
                    ->default(AnnouncementType::General)
                    ->required(),

                DateTimePicker::make('published_at')
                    ->label(__('Published At')),

                DateTimePicker::make('expires_at')
                    ->label(__('Expires At')),

                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }
}
