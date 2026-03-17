<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TextDirection: string implements HasLabel
{
    case Ltr = 'ltr';
    case Rtl = 'rtl';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ltr => __('Left to Right'),
            self::Rtl => __('Right to Left'),
        };
    }
}
