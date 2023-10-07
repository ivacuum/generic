<?php

namespace Ivacuum\Generic\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Socialite\VkProvider;
use Laravel\Socialite\Contracts\Factory;

class VkServiceProvider extends ServiceProvider
{
    protected $defer;

    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('vk', function (Application $app) use ($socialite) {
            return $socialite->buildProvider(VkProvider::class, $app['config']['services.vk']);
        });
    }

    public function register()
    {
        $this->app->scoped(VkProvider::class, function ($app) {
            $config = $app['config']['services.vk'];

            return new VkProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }

    public function provides()
    {
        return [VkProvider::class];
    }
}
