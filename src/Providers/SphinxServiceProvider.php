<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Services\Sphinx;
use Ivacuum\Generic\Sphinx\SphinxPdoConnection;

class SphinxServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->scoped(Sphinx::class, function () {
            $connection = new SphinxPdoConnection;
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
