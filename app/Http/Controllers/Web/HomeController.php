<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke() : RedirectResponse
    {
        if (auth()->guest()) {
            return redirect(route('login'));
        }

        if (auth()->user()) {
            return redirect(route('dashboard.home'));
        }

        Auth::logout();

        return redirect(route('login'));
    }
}
