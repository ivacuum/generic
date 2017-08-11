<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\WorkerStopping;
use Illuminate\Support\ServiceProvider;

class MetricsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (in_array($this->app->environment(), ['local', 'production'])) {
            $this->pushStats();
            $this->triggerStatsOnEvents();
        }
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
        \Event::listen(JobProcessed::class, function () {
            event(new \Ivacuum\Generic\Events\Stats\JobProcessed);
        });

        \Event::listen(MessageSent::class, function () {
            event(new \Ivacuum\Generic\Events\Stats\MailSent);
        });

        \Event::listen(NotificationSent::class, function () {
            event(new \Ivacuum\Generic\Events\Stats\NotificationSent);
        });

        \Event::listen(WorkerStopping::class, function () {
            \Log::info("Worker is stopping...\n");
        });
    }
}
