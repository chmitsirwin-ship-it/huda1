<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description', 'location'];

    protected function casts(): array
    {
        return [
            'status' => EventStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', EventStatus::Published)->orderBy('starts_at');
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->published()->where('starts_at', '>=', now());
    }
}
