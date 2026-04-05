<?php

namespace App\Filament\Admin\Resources\NewsCategories\Pages;

use App\Filament\Admin\Resources\NewsCategories\NewsCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsCategory extends CreateRecord
{
    protected static string $resource = NewsCategoryResource::class;
}
