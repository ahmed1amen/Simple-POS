<?php

namespace App\Providers;

use Doctrine\DBAL\Tools\Dumper;
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

        if (!backpack_auth()->check()) {
            backpack_auth()->loginUsingId(1);
        }
    }
}
