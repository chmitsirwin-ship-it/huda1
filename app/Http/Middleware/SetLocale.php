<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $languages = Language::where('is_active', true)->get();
        $activeCodes = $languages->pluck('code')->all();
        if ($request->filled('lang') && in_array($request->query('lang'), $activeCodes)) {
            session(['locale' => $request->query('lang')]);
        }

        $locale = session('locale');

        $language = $locale && in_array($locale, $activeCodes)
            ? $languages->firstWhere('code', $locale)
            : $languages->firstWhere('is_default', true);

        if ($language) {
            app()->setLocale($language->code);
            session(['locale' => $language->code]);
        }

        return $next($request);
    }
}
