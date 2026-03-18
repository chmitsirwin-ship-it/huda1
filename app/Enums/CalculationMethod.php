<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CalculationMethod: int implements HasLabel
{
    case Karachi = 1;
    case ISNA = 2;
    case MWL = 3;
    case UmmAlQura = 4;
    case Egyptian = 5;
    case Tehran = 7;
    case Gulf = 8;
    case Kuwait = 9;
    case Qatar = 10;
    case Singapore = 11;
    case France = 12;
    case Turkey = 13;
    case Russia = 14;
    case MoonsightingCommittee = 15;
    case Dubai = 16;
    case JAKIM = 17;
    case Tunisia = 18;
    case Algeria = 19;
    case KEMENAG = 20;
    case Morocco = 21;
    case Lisbon = 22;
    case Jordan = 23;

    public function getLabel(): string
    {
        return match ($this) {
            self::Karachi => __('University of Islamic Sciences, Karachi'),
            self::ISNA => __('ISNA (North America)'),
            self::MWL => __('Muslim World League'),
            self::UmmAlQura => __('Umm Al-Qura University, Makkah'),
            self::Egyptian => __('Egyptian General Authority of Survey'),
            self::Tehran => __('Institute of Geophysics, Tehran'),
            self::Gulf => __('Gulf Region'),
            self::Kuwait => __('Kuwait'),
            self::Qatar => __('Qatar'),
            self::Singapore => __('Majlis Ugama Islam Singapura'),
            self::France => __('Union Organization Islamique de France'),
            self::Turkey => __('Diyanet İşleri Başkanlığı, Turkey'),
            self::Russia => __('Spiritual Administration of Muslims of Russia'),
            self::MoonsightingCommittee => __('Moonsighting Committee Worldwide'),
            self::Dubai => __('Dubai (Experimental)'),
            self::JAKIM => __('JAKIM, Malaysia'),
            self::Tunisia => __('Tunisia'),
            self::Algeria => __('Algeria'),
            self::KEMENAG => __('KEMENAG, Indonesia'),
            self::Morocco => __('Morocco'),
            self::Lisbon => __('Comunidade Islamica de Lisboa'),
            self::Jordan => __('Ministry of Awqaf, Jordan'),
        };
    }
}