<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Content extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Content');
    }
}
