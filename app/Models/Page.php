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
            'is_home' => 'boolean',
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
        static::saving(function (self $page): void {
            if ($page->is_home) {
                $page->slug = 'home';
                $page->is_published = true;
                $page->show_in_nav = true;
                $page->sort_order = 0;
            }
        });

        static::saved(function (self $page): void {
            if ($page->is_home) {
                self::query()
                    ->whereKeyNot($page->getKey())
                    ->where('is_home', true)
                    ->update(['is_home' => false]);
            }

            Cache::forget("page_{$page->slug}_".app()->getLocale());
        });
    }
}
