<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class QuranVerse extends Model
{
    use HasTranslations;

    public array $translatable = ['translation', 'tafsir'];

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

    public function getRefAttribute(): string
    {
        return "{$this->surah_name} ({$this->surah_number}:{$this->verse_number})";
    }
}
