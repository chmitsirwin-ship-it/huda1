<?php

namespace App\Models;

use App\Enums\TextDirection;
use Illuminate\Database\Eloquent\Model;

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
