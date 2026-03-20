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
        return Cache::remember('prayer_today', 86400, fn () => PrayerTime::query()
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
            $date = Carbon::createFromDate($year, $month, $day);

            $response = $this->fetchFromApi($date->format('d-m-Y'));
            if ($response) {
                PrayerTime::updateOrCreate(
                    ['date' => $date->toDateString()],
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

        return [
            'fajr_adhan' => $timings['Fajr'] ?? null,
            'sunrise' => $timings['Sunrise'] ?? null,
            'dhuhr_adhan' => $timings['Dhuhr'] ?? null,
            'asr_adhan' => $timings['Asr'] ?? null,
            'maghrib_adhan' => $timings['Maghrib'] ?? null,
            'isha_adhan' => $timings['Isha'] ?? null,
        ];
    }
}
