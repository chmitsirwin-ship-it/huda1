<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function scopeForDate($query, string $date): void
    {
        $query->where('date', $date);
    }

    public function scopeForMonth($query, int $year, int $month): void
    {
        $query->whereYear('date', $year)->whereMonth('date', $month)->orderBy('date');
    }
}
