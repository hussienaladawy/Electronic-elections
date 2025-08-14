<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetSessionCookieByGuard
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('super_admin')->check()) {
            config(['session.cookie' => 'super_admin_session']);
        } elseif (Auth::guard('admin')->check()) {
            config(['session.cookie' => 'admin_session']);
        } elseif (Auth::guard('assistant')->check()) {
            config(['session.cookie' => 'assistant_session']);
        } elseif (Auth::guard('voter')->check()) {
            config(['session.cookie' => 'voter_session']);
        }

        return $next($request);
    }
}
