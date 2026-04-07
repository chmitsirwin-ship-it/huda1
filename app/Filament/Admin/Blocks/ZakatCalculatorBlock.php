<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class ZakatCalculatorBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')->label(__('Title')),
                    Textarea::make('description')->label(__('Description')),
                    TextInput::make('donation_button_text')->label(__('Donation Button Text')),
                ]),
            TextInput::make('nisab_gold')
                ->label(__('Gold Nisab'))
                ->numeric(),
            TextInput::make('nisab_silver')
                ->label(__('Silver Nisab'))
                ->numeric(),
            TextInput::make('currency_symbol')
                ->label(__('Currency Symbol'))
                ->default('$'),
            TextInput::make('zakat_fitr_amount')
                ->label(__('Zakat al-Fitr Amount'))
                ->numeric(),
            Toggle::make('show_fitr')
                ->label(__('Show Zakat al-Fitr'))
                ->default(true),
            TextInput::make('donation_url')
                ->label(__('Donation URL'))
                ->url(),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        foreach (['title', 'description', 'donation_button_text'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $data[$field][$locale] ?? collect($data[$field])->first(fn ($value) => filled($value)) ?? '';
            }
        }

        return $data;
    }

    public static function getCategory(): string
    {
        return Worship::class;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }

    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.png');
    }

    public static function getView(): ?string
    {
        return 'components.blocks.zakat-calculator';
    }
}
