<?php

namespace App\Filament\Admin\Resources\News\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Models\NewsCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('News Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title'))->required(),
                                Textarea::make('excerpt')->label(__('Excerpt'))->rows(3),
                                RichEditor::make('content')->label(__('Content'))->columnSpanFull(),
                                TextInput::make('meta_title')->label(__('Meta Title')),
                                TextInput::make('meta_description')->label(__('Meta Description')),
                            ]),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        FileUpload::make('featured_image')
                            ->label(__('Featured Image'))
                            ->image()
                            ->imageEditor()
                            ->directory('news')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])
                            ->columnSpanFull(),
                        Select::make('categories')
                            ->label(__('Categories'))
                            ->relationship(name: 'categories', titleAttribute: 'id', modifyQueryUsing: fn ($query) => $query->active())
                            ->getOptionLabelFromRecordUsing(fn (NewsCategory $record): string => (string) $record->name)
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Publishing'))
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label(__('Published At')),
                        Toggle::make('is_published')
                            ->label(__('Published')),
                    ]),
            ]);
    }
}
