<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCurrentUserPasswordRequest;
use App\Http\Requests\UpdateCurrentUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CurrentUserController extends Controller
{
    public function show() : JsonResponse
    {
        return UserResource::make(auth()->user())
            ->response()
            ->setStatusCode(200);
    }

    public function update(UpdateCurrentUserRequest $request) : JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'firstname' => $request->firstname(),
            'lastname' => $request->lastname(),
            'email' => $request->email(),
        ]);

        return UserResource::make(auth()->user())
            ->response()
            ->setStatusCode(200);
    }

    public function updatePassword(UpdateCurrentUserPasswordRequest $request) : JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([], 200);
    }
}
