<?php

namespace WxLogin\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
            __DIR__ . '/../config/' => config_path(),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("WxLogin\Repositories\Contracts\UnionInterface",'WxLogin\Repositories\Eloquent\UnionRepository');
    }
}