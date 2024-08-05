<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class CurrentUserController extends Controller
{
    public function show() : JsonResponse
    {
        return UserResource::make(auth()->user())
            ->response()
            ->setStatusCode(200);
    }
}
