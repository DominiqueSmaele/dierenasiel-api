<?php

namespace App\Http\Middleware;

use App\Events\ServingAdminDashboard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DispatchServingAdminDashboardEvent
{
    public function handle(Request $request, Closure $next) : Response
    {
        ServingAdminDashboard::dispatch($request);

        return $next($request);
    }
}
