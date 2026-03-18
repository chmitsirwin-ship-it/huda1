<?php

namespace App\Http\Controllers;

use App\Services\PrayerTimeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerTimeController extends Controller
{
    public function __construct(private PrayerTimeService $prayerTimeService) {}

    public function index(Request $request): View
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $prayerTimes = $this->prayerTimeService->getMonthPrayers($year, $month);
        $today = $this->prayerTimeService->getTodayPrayer();

        return view('pages.prayer-times.index', compact('prayerTimes', 'today', 'year', 'month'));
    }
}
