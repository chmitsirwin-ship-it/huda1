<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PrayerMethod: string implements HasLabel
{
    case Auto = 'auto';
    case Manual = 'manual';

    public function getLabel(): string
    {
        return match ($this) {
            self::Auto => __('Auto (API)'),
            self::Manual => __('Manual'),
        };
    }
}
