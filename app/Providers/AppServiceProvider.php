<?php

namespace App\Providers;

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
        //
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'phone' => \App\Models\Phone::class,
            'fit' => \App\Models\Fit::class,
            'accessory' => \App\Models\Accessory::class,
        ]);
    }
}
