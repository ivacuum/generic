<?php

namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class FastcgiServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!\App::runningInConsole() && function_exists('fastcgi_finish_request')) {
            register_shutdown_function('fastcgi_finish_request');
        }
    }
}
