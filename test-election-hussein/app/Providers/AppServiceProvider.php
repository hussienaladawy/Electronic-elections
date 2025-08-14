<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   
    public function boot()
    {
        // // غير اسم كوكي الجلسة حسب الجارد المسجل دخول منه
        if (Auth::guard('super_admin')->check()) {
            config(['session.cookie' => 'super_admin_session']);
        } elseif (Auth::guard('admin')->check()) {
            config(['session.cookie' => 'admin_session']);
        } elseif (Auth::guard('assistant')->check()) {
            config(['session.cookie' => 'assistant_session']);
        } elseif (Auth::guard('voter')->check()) {
            config(['session.cookie' => 'voter_session']);
        }
          Log::info('Super Admin:', ['user' => Auth::guard('super_admin')->user()]);
    Log::info('Admin:', ['user' => Auth::guard('admin')->user()]);
    Log::info('Voter:', ['user' => Auth::guard('voter')->user()]);
    Log::info('Assistant:', ['user' => Auth::guard('assistant')->user()]);
    }
}

