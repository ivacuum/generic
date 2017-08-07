<?php namespace Ivacuum\Generic\Utilities;

class RouteHelper
{
    public static function crud($controller, $prefix = null, $param = 'id')
    {
        $prefix = static::prefix($controller, $prefix);

        \Route::get("{$prefix}/", "{$controller}@index");
        \Route::post("{$prefix}/", "{$controller}@store");
        \Route::get("{$prefix}/create", "{$controller}@create");
        \Route::get("{$prefix}/{{$param}}", "{$controller}@show");
        \Route::put("{$prefix}/{{$param}}", "{$controller}@update");
        \Route::delete("{$prefix}/{{$param}}", "{$controller}@destroy");
        \Route::get("{$prefix}/{{$param}}/edit", "{$controller}@edit");
    }

    public static function withoutCreate($controller, $prefix = null, $param = 'id')
    {
        $prefix = static::prefix($controller, $prefix);

        \Route::get("{$prefix}/", "{$controller}@index");
        \Route::get("{$prefix}/{{$param}}", "{$controller}@show");
        \Route::put("{$prefix}/{{$param}}", "{$controller}@update");
        \Route::delete("{$prefix}/{{$param}}", "{$controller}@destroy");
        \Route::get("{$prefix}/{{$param}}/edit", "{$controller}@edit");
    }

    public static function withoutCreateAndEdit($controller, $prefix = null, $param = 'id')
    {
        $prefix = static::prefix($controller, $prefix);

        \Route::get("{$prefix}/", "{$controller}@index");
        \Route::get("{$prefix}/{{$param}}", "{$controller}@show");
        \Route::delete("{$prefix}/{{$param}}", "{$controller}@destroy");
    }

    protected static function prefix($controller, $prefix)
    {
        if ($prefix !== null) {
            return $prefix;
        }

        return implode('/', array_map(function ($ary) {
            return snake_case($ary, '-');
        }, explode('\\', str_after($controller, 'Acp\\'))));
    }
}
