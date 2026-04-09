<?php

namespace App\Http\Middleware;

use App\Support\PublicNavigation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSectionEnabled
{
    public function handle(Request $request, Closure $next, string $section): Response
    {
        abort_unless(PublicNavigation::isEnabled($section), 404);

        return $next($request);
    }
}
