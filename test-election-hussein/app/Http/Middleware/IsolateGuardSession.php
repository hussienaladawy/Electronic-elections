<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsolateGuardSession
{
    public function handle($request, Closure $next)
    {
        $guards = ['super_admin', 'admin', 'assistant', 'voter'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                session()->put('guard_session', [
                    'type' => $guard,
                    'user_id' => Auth::guard($guard)->id(),
                ]);
                break;
            }
        }

        return $next($request);
    }
}

