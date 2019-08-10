<?php namespace Ivacuum\Generic\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot(Request $baseRequest)
    {
        $request = $baseRequest->duplicate();

        $locale = $request->segment(1);
        $locales = config('cfg.locales');
        $defaultLocale = config('app.locale');

        if (is_array($locales) && in_array($locale, array_keys($locales))) {
            if ($locale !== $defaultLocale) {
                $baseRequest->server->set('LARAVEL_LOCALE', $locale);

                // /en/news => //news
                // /en?something => /?something
                $requestUri = substr_replace($request->getRequestUri(), '', 1, strlen($locale));

                // //news => /news
                $requestUri = strpos($requestUri, '//') === 0 ? substr_replace($requestUri, '', 0, 1) : $requestUri;

                // Так можно кэшировать маршруты без указания локализации
                // Но приходится сложнее строить ссылки
                $baseRequest->server->set('REQUEST_URI', $requestUri);
            }
        } else {
            $locale = $defaultLocale;
        }

        setlocale(LC_ALL, config("cfg.locales.{$locale}.posix"));
        setlocale(LC_NUMERIC, 'C');
        Carbon::setLocale($locale);

        if ($locale !== $defaultLocale) {
            \App::setLocale($locale);
        }
    }

    public function register()
    {
    }
}
