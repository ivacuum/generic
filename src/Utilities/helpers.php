<?php

if (!function_exists('canonical')) {
    /**
     * Канонический адрес текущей страницы
     *
     * @return string
     */
    function canonical(): string
    {
        $request = app('request');

        $locale = $request->server->get('LARAVEL_LOCALE') ?? null;
        $prefix = $locale ? "/{$locale}" : '';

        $path = $prefix.$request->getPathInfo();
        $suffix = $path === '/' ? '/' : '';

        return rtrim($request->root().$prefix.$request->getPathInfo(), '/').$suffix;
    }
}

if (!function_exists('fullUrl')) {
    /**
     * Адрес текущей страницы с произвольными параметрами
     *
     * @param  array  $params
     * @return string
     */
    function fullUrl(array $params = []): string
    {
        $request = app('request');

        if (null === $qs = $request->getQueryString()) {
            if (empty($params)) {
                return canonical();
            }

            return canonical() . '?' . http_build_query($params);
        }

        return canonical() . '?' . http_build_query(array_merge($request->query(), $params));
    }
}

if (!function_exists('path')) {
    /**
     * Адрес страницы, соответствующий контроллеру
     *
     * @param  string $name
     * @param  array|string  $parameters
     * @param  bool   $absolute
     * @return string
     */
    function path(string $name, $parameters = [], bool $absolute = false): string
    {
        static $prefix;

        if (is_null($prefix)) {
            $locale = app('request')->server->get('LARAVEL_LOCALE') ?? null;
            $prefix = $locale ? "/{$locale}" : '';
        }

        if (!$prefix) {
            return app('url')->action($name, $parameters, $absolute);
        }

        $action = app('url')->action($name, $parameters, false);

        return ($absolute ? app('request')->root() : '') . $prefix . ($action === '/' ? '' : $action);
    }
}

if (!function_exists('path_locale')) {
    /**
     * Адрес страницы, соответствующий контроллеру
     *
     * @param  string $name
     * @param  array|string  $parameters
     * @param  bool   $absolute
     * @param  string $locale
     * @return string
     */
    function path_locale(string $name, $parameters = [], bool $absolute = false, string $locale = ''): string
    {
        $prefix = $locale !== 'ru' ? "/{$locale}" : '';

        if (!$prefix) {
            return app('url')->action($name, $parameters, $absolute);
        }

        $action = app('url')->action($name, $parameters, false);

        return ($absolute ? app('request')->root() : '') . $prefix . ($action === '/' ? '' : $action);
    }
}
