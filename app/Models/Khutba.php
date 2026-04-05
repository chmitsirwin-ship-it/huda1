<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Khutba extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'topic', 'summary', 'speaker', 'content'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(KhutbaCategory::class, 'khutba_category_khutba');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->orderByDesc('date');
    }
}
