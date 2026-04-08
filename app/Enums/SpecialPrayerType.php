<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SpecialPrayerType: string implements HasLabel
{
    case Ramadan = 'ramadan';
    case Eid = 'eid';
    case Weekly = 'weekly';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ramadan => __('Ramadan'),
            self::Eid => __('Eid'),
            self::Weekly => __('Weekly'),
            self::Other => __('Other'),
        };
    }
}
