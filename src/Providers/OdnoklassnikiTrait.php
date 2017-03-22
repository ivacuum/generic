<?php namespace Ivacuum\Generic\Providers;

use Ivacuum\Generic\Socialite\OdnoklassnikiProvider;

trait OdnoklassnikiTrait
{
    /* @var \Illuminate\Foundation\Application */
    protected $app;

    protected function odnoklassniki()
    {
        $this->app->singleton(OdnoklassnikiProvider::class, function ($app) {
            $config = $app['config']['services.odnoklassniki'];

            return new OdnoklassnikiProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}
