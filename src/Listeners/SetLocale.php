<?php namespace Ivacuum\Generic\Listeners;

use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\Carbon;

class SetLocale
{
    public function handle(LocaleUpdated $event)
    {
        if (!config("cfg.locales.{$event->locale}.posix")) {
            return;
        }

        setlocale(LC_ALL, config("cfg.locales.{$event->locale}.posix"));
        Carbon::setLocale($event->locale);
    }
}
