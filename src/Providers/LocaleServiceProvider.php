<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot(Request $base_request)
    {
        $request = $base_request->duplicate();

        $locale = $request->segment(1);
        $locales = config('cfg.locales');
        $default_locale = config('app.locale');

        if (is_array($locales) && in_array($locale, array_keys($locales))) {
            if ($locale !== $default_locale) {
                $base_request->server->set('LARAVEL_LOCALE', $locale);

                // /en/news => //news
                // /en?something => /?something
                $request_uri = substr_replace($request->getRequestUri(), '', 1, strlen($locale));

                // //news => /news
                $request_uri = strpos($request_uri, '//') === 0 ? substr_replace($request_uri, '', 0, 1) : $request_uri;

                // Так можно кэшировать маршруты без указания локализации
                // Но приходится сложнее строить ссылки
                $base_request->server->set('REQUEST_URI', $request_uri);
            }
        } else {
            $locale = $default_locale;
        }

        setlocale(LC_ALL, config("cfg.locales.{$locale}.posix"));
        Carbon::setLocale($locale);

        if ($locale !== $default_locale) {
            \App::setLocale($locale);
        }
    }

    public function register()
    {
    }
}
