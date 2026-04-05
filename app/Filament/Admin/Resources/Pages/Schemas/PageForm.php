<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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

                        Toggle::make('is_home')
                            ->label(__('Is Home'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, bool $state): void {
                                if (! $state) {
                                    return;
                                }

                                $set('slug', 'home');
                                $set('is_published', true);
                                $set('show_in_nav', true);
                                $set('sort_order', 0);
                            }),

                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (Get $get): bool => (bool) $get('is_home'))
                            ->dehydrated(),
                        Grid::make(2)->schema([

                            Toggle::make('is_published')
                                ->label(__('Published'))
                                ->disabled(fn (Get $get): bool => (bool) $get('is_home')),

                            Toggle::make('show_in_nav')
                                ->label(__('Show in Navigation'))
                                ->disabled(fn (Get $get): bool => (bool) $get('is_home')),
                        ])
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
