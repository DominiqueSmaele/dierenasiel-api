<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LogoutController extends Controller
{
    public function store() : Response
    {
        auth()->user()->token()->revoke();

        return response(null)->setStatusCode(204);
    }
}
