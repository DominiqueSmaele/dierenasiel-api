<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(LoginUserRequest $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        $user->tokens()->delete();

        $token = $user->createToken('access_token')->accessToken;

        return response()->json(['token' => $token], 200);
    }
}
