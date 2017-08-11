<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Ivacuum\Generic\Services\Telegram;

class QueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Queue::failing(function (JobFailed $event) {
            app(Telegram::class)->notifyAdmin("{$event->exception->getMessage()}\n{$event->exception->getFile()}:{$event->exception->getLine()}");
        });
    }
}
