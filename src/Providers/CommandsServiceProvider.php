<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Support\ServiceProvider;

class CommandsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Ivacuum\Generic\Commands\MakeAcpSection::class,
                \Ivacuum\Generic\Commands\MetricDelete::class,
                \Ivacuum\Generic\Commands\MetricRename::class,
                \Ivacuum\Generic\Commands\NotificationsPurge::class,
                \Ivacuum\Generic\Commands\PasswordRemindersPurge::class,
            ]);
        }
    }
}
