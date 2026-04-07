<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Contact extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Contact & Location');
    }
}
