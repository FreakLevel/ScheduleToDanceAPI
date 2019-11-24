<?php

namespace App\Providers;

use App\Appointment;
use App\Observers\AppointmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Appointment::observe(AppointmentObserver::class);
    }
}
