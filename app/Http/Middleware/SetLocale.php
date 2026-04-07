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

        if ($request->has('lang') && in_array($request->get('lang'), $activeCodes)) {
            session(['locale' => $request->get('lang')]);
        }

        $locale = session('locale');

        $language = match (true) {
            $locale && in_array($locale, $activeCodes) => $languages->firstWhere('code', $locale),
            in_array($accept = substr($request->header('Accept-Language', ''), 0, 2), $activeCodes) => $languages->firstWhere('code', $accept),
            default => $languages->firstWhere('is_default', true),
        };

        if ($language) {
            app()->setLocale($language->code);
            session(['locale' => $language->code]);
        }

        return $next($request);
    }
}
