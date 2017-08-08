<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\ServiceProvider;

class MetricsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->environment('local', 'production')) {
            $this->pushStats();
            $this->triggerStatsOnEvents();
            $this->export();
        }
    }

    protected function export()
    {
        register_shutdown_function(function () {
            \MetricsHelper::export();
        });
    }

    protected function pushStats()
    {
        \Event::listen([
            'App\Events\Stats\*',
            'Ivacuum\Generic\Events\Stats\*'
        ], function ($name, array $data) {
            $basename = class_basename($name);

            \MetricsHelper::push([
                'event' => $basename,
                'data' => $data[0]
            ]);
        });
    }

    protected function triggerStatsOnEvents()
    {
        \Event::listen(NotificationSent::class, function () {
            event(new \Ivacuum\Generic\Events\Stats\NotificationSent);
        });
    }
}
