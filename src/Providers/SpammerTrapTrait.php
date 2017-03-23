<?php namespace Ivacuum\Generic\Providers;

trait SpammerTrapTrait
{
    /* @var \Illuminate\Foundation\Application */
    protected $app;

    protected function trap()
    {
        $this->app->booted(function ($app) {
            $app['validator']->extend('empty', function ($attr, $value, $params) {
                event(new \Ivacuum\Generic\Events\Stats\SpammerTrapped);

                return empty($value);
            }, 'Читер');
        });
    }
}
