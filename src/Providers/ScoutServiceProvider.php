<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Scout\SphinxEngine;
use Laravel\Scout\EngineManager;

class ScoutServiceProvider extends ServiceProvider
{
    public function boot()
    {
        resolve(EngineManager::class)->extend('sphinx', fn () => resolve(SphinxEngine::class));
    }
}
