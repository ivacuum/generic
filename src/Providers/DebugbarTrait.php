<?php namespace Ivacuum\Generic\Providers;

trait DebugbarTrait
{
    /* @var \Illuminate\Foundation\Application */
    protected $app;

    protected function debugbar()
    {
        if ($this->app->isLocal()) {
            if (\Request::cookie('debugbar', false)) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }

            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
