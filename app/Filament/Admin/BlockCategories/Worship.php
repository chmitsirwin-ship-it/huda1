<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Worship extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Worship & Guidance');
    }
}
