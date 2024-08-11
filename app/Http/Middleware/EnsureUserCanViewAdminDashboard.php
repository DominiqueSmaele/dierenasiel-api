<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanViewAdminDashboard
{
    public function handle(Request $request, Closure $next) : Response
    {
        if (auth()->user()->cannot('viewAdminDashboard')) {
            Auth::logout();

            return redirect(route('login'));
        }

        return $next($request);
    }
}
