<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Media extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Media');
    }
}
