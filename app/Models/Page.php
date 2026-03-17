<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Redberry\PageBuilderPlugin\Traits\HasPageBuilder;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasPageBuilder;
    use HasTranslations;

    public array $translatable = ['title', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_in_nav' => 'boolean',
        ];
    }

    public function scopePublished($query): void
    {
        $query->where('is_published', true)->orderBy('sort_order');
    }

    public function scopeNav($query): void
    {
        $query->published()->where('show_in_nav', true);
    }

    protected static function booted(): void
    {
        static::saved(fn (self $page) => Cache::forget("page_{$page->slug}_".app()->getLocale()));
    }
}
