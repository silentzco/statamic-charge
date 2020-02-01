<?php

namespace Silentz\Charge\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Statamic\Exceptions\AuthorizationException;

class HasSubscription
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->subscription($request->name)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
