<?php

if (!function_exists('canonical')) {
    /**
     * Канонический адрес текущей страницы
     *
     * @return string
     */
    function canonical()
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
    function fullUrl(array $params = [])
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
     * @param  array  $parameters
     * @param  bool   $absolute
     * @return string
     */
    function path($name, $parameters = [], $absolute = false)
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
