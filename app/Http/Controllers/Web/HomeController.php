<?php

namespace App\Http\Controllers\Web;

use App\Enums\Permission;
use App\Enums\ShelterPermission;
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

        if (auth()->user()->hasPermission(Permission::manageAllShelters)) {
            return redirect(route('global.home'));
        }

        if (auth()->user()->hasPermission(ShelterPermission::manageShelter, auth()->user()->shelter)) {
            return redirect(route('shelter.home', auth()->user()->shelter));
        }

        Auth::logout();

        return redirect(route('login'));
    }
}
