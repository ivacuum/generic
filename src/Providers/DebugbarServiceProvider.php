<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class DebugbarServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->isLocal()) {
            if (\Request::cookie('debugbar', false)) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }

            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
