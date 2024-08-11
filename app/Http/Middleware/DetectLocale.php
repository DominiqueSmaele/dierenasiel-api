<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->locale() ?? config('app.fallback_locale');

        $user = $request->user('api') ?? $request->user();
        $user?->update(['locale' => $locale]);

        app()->setLocale(in_array($locale, config('app.supported_locales')) ? $locale : config('app.fallback_locale'));

        return $next($request);
    }
}
