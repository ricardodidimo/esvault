<?php

namespace App\Http\Middleware;

use App\Exceptions\AppForbiddenException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Routing\Pipeline;

class CustomEnsureEmailVerified
{
    public function handle($request, $next)
    {
        if (
            ! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())
        ) {
            throw new AppForbiddenException('You must verify your email first.');
        }

        return $next($request);
    }
}
