<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCustom extends Authenticate
{
    protected function redirectTo(Request $request)
    {
        if (! $request->expectsJson()) {
            return route('user.loginDisplay');
        }
    }
}
