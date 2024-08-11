<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastActiveAtOfUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api')->user() ?? auth()->user();
        $user?->update(['last_active_at' => now()->startOfHour()]);

        return $next($request);
    }
}
