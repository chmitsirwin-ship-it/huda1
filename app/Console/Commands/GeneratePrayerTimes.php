<?php

namespace App\Console\Commands;

use App\Services\PrayerTimeService;
use Illuminate\Console\Command;

class GeneratePrayerTimes extends Command
{
    protected $signature = 'mosque:generate-prayer-times
                            {--year= : Year to generate (defaults to current year)}
                            {--month= : Month to generate (defaults to current month)}';

    protected $description = 'Generate prayer times for a given month using the API';

    public function handle(PrayerTimeService $service): int
    {
        $year = (int) ($this->option('year') ?? now()->year);
        $month = (int) ($this->option('month') ?? now()->month);

        $this->info("Generating prayer times for {$year}-{$month}...");

        $generated = $service->generateMonth($year, $month);

        $this->info("Generated {$generated} prayer time entries.");

        return self::SUCCESS;
    }
}
