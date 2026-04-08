<?php

namespace App\Services;

use App\Models\PrayerTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimeService
{
    public function getTodayPrayer(): ?PrayerTime
    {
        return Cache::flexible('prayer_today', [90,180], fn () => PrayerTime::query()
            ->whereDate('date', today())
            ->first());
    }

    public function getMonthPrayers(int $year, int $month): Collection
    {
        return PrayerTime::forMonth($year, $month)->get();
    }

    public function generateMonth(int $year, int $month): int
    {
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $generated = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $gregorianDate = Carbon::createFromDate($year, $month, $day);
            $formattedGregorianDate = $gregorianDate->format('d-m-Y');

            $response = $this->fetchFromApi($formattedGregorianDate);
            if ($response) {
                PrayerTime::updateOrCreate(
                    ['date' => $gregorianDate->toDateString()],
                    $response
                );
                $generated++;
            }
        }

        Cache::forget('prayer_today');

        return $generated;
    }

    private function fetchFromApi(string $date): ?array
    {
        $response = Http::get('https://api.aladhan.com/v1/timings/'.$date, [
            'latitude' => setting('location.latitude') ?? 40.7128,
            'longitude' => setting('location.longitude') ?? -74.0060,
            'method' => setting('prayer.calculation_method') ?? 2,
        ]);

        if (! $response->successful()) {
            return null;
        }

        $timings = $response->json('data.timings');
        $adjustment = (int) setting('prayer.adjustment_factor');

        return [
            'fajr_adhan' => $this->adjustTime($timings['Fajr'] ?? null, $adjustment),
            'sunrise' => $this->adjustTime($timings['Sunrise'] ?? null, $adjustment),
            'dhuhr_adhan' => $this->adjustTime($timings['Dhuhr'] ?? null, $adjustment),
            'asr_adhan' => $this->adjustTime($timings['Asr'] ?? null, $adjustment),
            'maghrib_adhan' => $this->adjustTime($timings['Maghrib'] ?? null, $adjustment),
            'isha_adhan' => $this->adjustTime($timings['Isha'] ?? null, $adjustment),
        ];
    }

    private function adjustTime(?string $time, int $minutes): ?string
    {
        if (! $time || $minutes === 0) {
            return $time;
        }

        return Carbon::parse($time)->addMinutes($minutes)->format('H:i');
    }
}
