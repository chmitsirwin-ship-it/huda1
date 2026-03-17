<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Hadith extends Model
{
    use HasTranslations;

    public array $translatable = ['narrator', 'text', 'source', 'grade'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function scopeFeatured($query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeByCollection($query, string $collection): void
    {
        $query->where('collection', $collection);
    }
}
