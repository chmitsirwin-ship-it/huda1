<?php

namespace App\Models;

use App\Enums\SpecialPrayerType;
use ElipZis\Cacheable\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SpecialPrayer extends Model
{
    use Cacheable;
    use HasTranslations;

    public array $translatable = ['name', 'group', 'description'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'type' => SpecialPrayerType::class,
            'is_recurring' => 'boolean',
            'location' => 'array',
        ];
    }

    public function scopeForDate(Builder $query, string $date): void
    {
        $query->where('date', $date);
    }

    public function scopeUpcoming(Builder $query): void
    {
        $query->where('date', '>=', today())->orderBy('date')->orderBy('time');
    }

    public function scopeForType(Builder $query, SpecialPrayerType $type): void
    {
        $query->where('type', $type);
    }

    public function scopeForGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }
}
