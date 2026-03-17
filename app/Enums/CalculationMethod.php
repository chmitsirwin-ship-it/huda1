<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CalculationMethod: int implements HasLabel
{
    case Shafi = 0;
    case ISNA = 2;
    case MWL = 3;
    case UmmAlQura = 4;
    case Egyptian = 5;
    case Tehran = 7;
    case Karachi = 1;
    case Dubai = 8;
    case Kuwait = 9;
    case Qatar = 10;
    case Singapore = 11;
    case France = 12;
    case Turkey = 13;
    case Russia = 14;

    public function getLabel(): string
    {
        return match ($this) {
            self::Shafi => __('Shafi (Standard)'),
            self::ISNA => __('ISNA (North America)'),
            self::MWL => __('Muslim World League'),
            self::UmmAlQura => __('Umm Al-Qura (Makkah)'),
            self::Egyptian => __('Egyptian General Authority'),
            self::Tehran => __('Tehran (Iran)'),
            self::Karachi => __('Karachi (Pakistan)'),
            self::Dubai => __('Dubai'),
            self::Kuwait => __('Kuwait'),
            self::Qatar => __('Qatar'),
            self::Singapore => __('Singapore'),
            self::France => __('France'),
            self::Turkey => __('Turkey'),
            self::Russia => __('Russia'),
        };
    }
}
