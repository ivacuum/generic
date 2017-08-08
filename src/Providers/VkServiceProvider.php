<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Socialite\VkProvider;

class VkServiceProvider extends ServiceProvider
{
    protected $defer;

    public function register()
    {
        $this->app->singleton(VkProvider::class, function ($app) {
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
