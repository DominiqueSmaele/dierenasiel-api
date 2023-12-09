<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \Laravel\Passport\Exceptions\OAuthServerException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
}
