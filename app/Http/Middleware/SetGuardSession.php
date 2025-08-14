<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class SetGuardSession
{
    public function handle($request, Closure $next)
    {
        // نقرأ الـ middleware اللي على الروت
        $routeMiddleware = Route::current()->gatherMiddleware();

        // ندور على middleware يبدأ بـ "auth:"
        $guard = null;
        foreach ($routeMiddleware as $middleware) {
            if (strpos($middleware, 'auth:') === 0) {
                $guard = explode(':', $middleware)[1] ?? null;
                break;
            }
        }

        // لو لقى guard غيّر اسم الكوكي
        if ($guard) {
            Config::set('session.cookie', $guard . '_session');
        }

        return $next($request);
    }
}