<?php

namespace App\Providers;

use Carbon\Carbon;
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
    public function boot(): void
    {
           // Atur locale Carbon (misal: Indonesia)
    Carbon::setLocale('id');

    // Atur timezone global (jika perlu)
    date_default_timezone_set('Asia/Jakarta');
    }
}
