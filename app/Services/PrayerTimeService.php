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
        return Cache::remember(
            'prayer_today',
            now()->endOfDay()->diffInSeconds(now()),
            fn () => PrayerTime::query()
                ->whereDate('date', today())
                ->first()
        );
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

            $response = $this->fetchFromApi($formattedGregorianDate, $gregorianDate);
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

    private function fetchFromApi(string $date, Carbon $carbonDate): ?array
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
        $iqamahOffset = (int) setting('prayer.iqamah_offset');

        $fajrAdhan = $this->adjustTime($timings['Fajr'] ?? null, $adjustment);
        $dhuhrAdhan = $this->adjustTime($timings['Dhuhr'] ?? null, $adjustment);
        $asrAdhan = $this->adjustTime($timings['Asr'] ?? null, $adjustment);
        $maghribAdhan = $this->adjustTime($timings['Maghrib'] ?? null, $adjustment);
        $ishaAdhan = $this->adjustTime($timings['Isha'] ?? null, $adjustment);

        $data = [
            'fajr_adhan' => $fajrAdhan,
            'fajr_iqamah' => $this->adjustTime($fajrAdhan, $iqamahOffset),
            'sunrise' => $this->adjustTime($timings['Sunrise'] ?? null, $adjustment),
            'dhuhr_adhan' => $dhuhrAdhan,
            'dhuhr_iqamah' => $this->adjustTime($dhuhrAdhan, $iqamahOffset),
            'asr_adhan' => $asrAdhan,
            'asr_iqamah' => $this->adjustTime($asrAdhan, $iqamahOffset),
            'maghrib_adhan' => $maghribAdhan,
            'maghrib_iqamah' => $this->adjustTime($maghribAdhan, $iqamahOffset),
            'isha_adhan' => $ishaAdhan,
            'isha_iqamah' => $this->adjustTime($ishaAdhan, $iqamahOffset),
        ];

        if ($carbonDate->isFriday() && $dhuhrAdhan) {
            $jummahOffset = (int) setting('prayer.jummah_offset');
            $data['jummah_adhan'] = $dhuhrAdhan;
            $data['jummah_khutba_time'] = $this->adjustTime($dhuhrAdhan, -15);
            $data['jummah_iqamah'] = $this->adjustTime($dhuhrAdhan, $jummahOffset ?: $iqamahOffset);
        }

        return $data;
    }

    private function adjustTime(?string $time, int $minutes): ?string
    {
        if (! $time || $minutes === 0) {
            return $time;
        }

        return Carbon::parse($time)->addMinutes($minutes)->format('H:i');
    }
}
