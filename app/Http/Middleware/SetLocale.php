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
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            $language = Language::where('code', $lang)->where('is_active', true)->first();

            if ($language) {
                session(['locale' => $language->code]);
            }
        }

        $locale = session('locale');

        if ($locale) {
            $language = Language::where('code', $locale)->where('is_active', true)->first();
        }

        if (empty($language)) {
            $acceptLanguage = substr($request->header('Accept-Language', ''), 0, 2);
            $language = Language::where('code', $acceptLanguage)->where('is_active', true)->first();
        }

        if (empty($language)) {
            $language = Language::where('is_default', true)->first();
        }

        if ($language) {
            app()->setLocale($language->code);
            session(['locale' => $language->code]);
        }

        return $next($request);
    }
}
