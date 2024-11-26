<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $pendingAppointments = 0;

            if (Auth::check()) {
                $pendingAppointments = Appointment::where('status', 'pending')
                    ->where('userProf_id', Auth::id())
                    ->count();
            }

            $view->with('pendingAppointments', $pendingAppointments);
        });
    }

    public function register(): void
    {
        //
    }
}
