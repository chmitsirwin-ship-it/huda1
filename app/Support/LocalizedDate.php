<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class LocalizedDate
{
    public const DATE_FORMAT = 'j M Y';

    public const TIME_FORMAT = 'g:i A';

    public const DATE_TIME_FORMAT = self::DATE_FORMAT.' '.self::TIME_FORMAT;

    public static function date(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat(self::DATE_FORMAT);
    }

    public static function time(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat(self::TIME_FORMAT);
    }

    public static function dateTime(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat(self::DATE_TIME_FORMAT);
    }

    public static function weekday(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat('l');
    }

    public static function monthYear(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat('M Y');
    }

    public static function day(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat('j');
    }

    public static function monthShort(mixed $value): ?string
    {
        return self::parse($value)?->translatedFormat('M');
    }

    private static function parse(mixed $value): ?CarbonInterface
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof CarbonInterface) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value);
        }

        return Carbon::parse($value);
    }
}
