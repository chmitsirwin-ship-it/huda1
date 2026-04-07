<?php

namespace App\Filament\Admin\BlockCategories;

use Redberry\PageBuilderPlugin\Abstracts\BaseBlockCategory;

class Community extends BaseBlockCategory
{
    public static function getCategoryName(): string
    {
        return __('Community & Outreach');
    }
}
