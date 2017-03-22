<?php namespace Ivacuum\Generic\Providers;

use Carbon\Carbon;

trait LocaleTrait
{
    protected function locale()
    {
        $locale = \Request::segment(1);
        $locales = config('cfg.locales');
        $default_locale = config('app.locale');

        if (is_array($locales) && in_array($locale, array_keys(config('cfg.locales')))) {
        } else {
            $locale = $default_locale;
        }

        setlocale(LC_ALL, config("cfg.locales.{$locale}.posix"));
        Carbon::setLocale($locale);

        if ($locale === $default_locale) {
            $locale = '';
        } else {
            \App::setLocale($locale);
        }

        return $locale;
    }

    protected function bladeLang()
    {
        \Blade::directive('ru', function ($expression) {
            return '<?php if ($locale === \'ru\'): ?>';
        });

        \Blade::directive('en', function ($expression) {
            return '<?php elseif ($locale === \'en\'): ?>';
        });

        \Blade::directive('de', function ($expression) {
            return '<?php elseif ($locale === \'de\'): ?>';
        });

        \Blade::directive('endlang', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    protected function bladeSvg()
    {
        \Blade::directive('svg', function ($expression) {
            return "<?php require base_path(\"resources/svg/$expression.html\"); ?>";
        });
    }
}
