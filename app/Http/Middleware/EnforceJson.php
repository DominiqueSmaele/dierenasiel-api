<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceJson
{
    protected array $except = [];

    public function handle(Request $request, Closure $next) : Response
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        if ($request->format() !== 'json') {
            abort(400, "The 'Accept' header must equal 'application/json', '{$request->header('accept')}' found.");
        }

        return $next($request);
    }

    protected function inExceptArray(Request $request) : bool
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
