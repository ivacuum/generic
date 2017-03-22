<?php namespace Ivacuum\Generic\Providers;

use Ivacuum\Generic\Socialite\VkProvider;

trait VkTrait
{
    /* @var \Illuminate\Foundation\Application */
    protected $app;

    protected function vk()
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
}
