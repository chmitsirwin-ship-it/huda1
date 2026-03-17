<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MediaItem extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'alt_text'];

    protected function casts(): array
    {
        return [
            'type' => MediaType::class,
        ];
    }

    public function scopeImages($query): void
    {
        $query->where('type', MediaType::Image)->orderBy('sort_order');
    }

    public function scopeByCollection($query, string $collection): void
    {
        $query->where('collection', $collection)->orderBy('sort_order');
    }
}
