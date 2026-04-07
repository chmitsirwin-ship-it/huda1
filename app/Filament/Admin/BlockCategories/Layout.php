<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Layout extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Layout & Highlights');
    }
}
