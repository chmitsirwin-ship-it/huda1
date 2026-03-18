<?php

namespace App\Filament\Admin\Resources\ContactSubmissions\Schemas;

use App\Enums\ContactSubmissionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Contact Info'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->disabled(),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->disabled(),
                        TextInput::make('phone')
                            ->label(__('Phone'))
                            ->disabled(),
                    ]),

                Section::make(__('Message'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('Subject'))
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label(__('Message'))
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('Status'))
                    ->aside()
                    ->schema([
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(ContactSubmissionStatus::class),
                        DateTimePicker::make('read_at')
                            ->label(__('Read At'))
                            ->disabled(),
                    ]),
            ]);
    }
}
