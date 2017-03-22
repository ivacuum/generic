<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Notifications\Events\NotificationSent;

trait MetricsTrait
{
    /* @var \Illuminate\Foundation\Application */
    protected $app;

    protected function metrics()
    {
        if ($this->app->environment('local', 'production')) {
            \Event::listen(['App\Events\Stats\*', 'Ivacuum\Generic\Events\Stats\*'], function ($name, array $data) {
                $basename = class_basename($name);

                \MetricsHelper::push(['event' => $basename, 'data' => $data[0]]);
            });

            \Event::listen(NotificationSent::class, function () {
                event(new \Ivacuum\Generic\Events\Stats\NotificationSent);
            });

            register_shutdown_function(function () {
                \MetricsHelper::export();
            });
        }
    }
}
