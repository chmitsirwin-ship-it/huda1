<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Redberry\PageBuilderPlugin\Components\Forms\Actions\EditPageBuilderBlockAction;
use Redberry\PageBuilderPlugin\Components\Forms\Actions\SelectBlockAction;
use Redberry\PageBuilderPlugin\Components\Forms\PageBuilder;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Page Details'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title'))->required(),
                            ]),

                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('sort_order')
                            ->label(__('Sort Order'))
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label(__('Published')),

                        Toggle::make('show_in_nav')
                            ->label(__('Show in Navigation')),
                    ]),

                Section::make(__('SEO'))
                    ->schema([
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('meta_title')->label(__('Meta Title')),
                                Textarea::make('meta_description')->label(__('Meta Description')),
                            ]),
                    ]),

                Section::make(__('Content Blocks'))
                    ->schema([
                        PageBuilder::make('pageBuilderBlocks')
                            ->relationship()
                            ->label(__('Content'))
                            ->reorderable()
                            ->renderWithThumbnails()
                            ->translateLabel()
                            ->selectBlockAction(function (SelectBlockAction $action) {
                                return $action->label(__('Add New Block'));
                            })
                            ->editAction(fn (EditPageBuilderBlockAction $action) => $action->label(__('Edit Block'))),
                    ]),
            ]);
    }
}
