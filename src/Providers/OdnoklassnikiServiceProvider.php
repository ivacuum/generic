<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Socialite\OdnoklassnikiProvider;

class OdnoklassnikiServiceProvider extends ServiceProvider
{
    protected $defer;

    public function register()
    {
        $this->app->scoped(OdnoklassnikiProvider::class, function ($app) {
            $config = $app['config']['services.odnoklassniki'];

            return new OdnoklassnikiProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }

    public function provides()
    {
        return [OdnoklassnikiProvider::class];
    }
}
