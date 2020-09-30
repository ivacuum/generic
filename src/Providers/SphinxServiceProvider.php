<?php namespace Ivacuum\Generic\Providers;

use Foolz\SphinxQL\Drivers\Pdo\Connection;
use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Services\Sphinx;

class SphinxServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Sphinx::class, function () {
            $connection = new Connection;
            $connection->setParams([
                'host' => config('cfg.sphinx.host'),
                'port' => config('cfg.sphinx.port'),
                'socket' => config('cfg.sphinx.socket'),
            ]);

            return new Sphinx($connection);
        });
    }

    public function provides()
    {
        return [Sphinx::class];
    }
}
