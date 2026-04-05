<?php

namespace App\Filament\Admin\Resources\NewsCategories\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Models\NewsCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsCategoryForm
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
                        Select::make('parent_id')
                            ->label(__('Parent Category'))
                            ->options(fn (): array => NewsCategory::query()
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn (NewsCategory $category): array => [$category->id => (string) $category->name])
                                ->all())
                            ->searchable(),
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
