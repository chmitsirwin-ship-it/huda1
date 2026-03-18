<?php

namespace App\Models;

use App\Enums\TextDirection;
use App\Observers\LanguageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([LanguageObserver::class])]
class Language extends Model
{

    protected function casts(): array
    {
        return [
            'direction' => TextDirection::class,
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function getDefault(): self
    {
        return static::where('is_default', true)->firstOrFail();
    }
}
