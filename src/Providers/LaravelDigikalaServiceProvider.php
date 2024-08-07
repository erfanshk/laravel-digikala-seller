<?php

namespace Erfanshk\LaravelDigikala\Providers;


use Illuminate\Support\ServiceProvider;

class LaravelDigikalaServiceProvider extends ServiceProvider {

    public function register() :void
    {
        $this->publishes([
            __DIR__. '/../Configs/digikala.php' => config_path('digikala.php')
        ],'config');
    }

    public function boot() : void
    {

    }
}
