<?php

namespace App\Models;

use App\Enums\CalculationMethod;
use App\Enums\PrayerMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class MosqueSettings extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description', 'address', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'prayer_method' => PrayerMethod::class,
            'calculation_method' => CalculationMethod::class,
        ];
    }

    public static function instance(): self
    {
        return Cache::remember('mosque_settings', 3600, fn () => static::firstOrFail());
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('mosque_settings'));
    }
}
