<?php namespace Ivacuum\Generic\Utilities;

class RouteHelper
{
    public static function crud(string $controller, ?string $prefix = null, string $param = 'id'): void
    {
        $prefix ??= NamingHelper::acpControllerPrefix($controller);

        \Route::get("{$prefix}/", [$controller, 'index']);
        \Route::post("{$prefix}/", [$controller, 'store']);
        \Route::get("{$prefix}/create", [$controller, 'create']);
        \Route::get("{$prefix}/{{$param}}", [$controller, 'show']);
        \Route::put("{$prefix}/{{$param}}", [$controller, 'update']);
        \Route::delete("{$prefix}/{{$param}}", [$controller, 'destroy']);
        \Route::get("{$prefix}/{{$param}}/edit", [$controller, 'edit']);
    }

    public static function withoutCreate(string $controller, ?string $prefix = null, string $param = 'id'): void
    {
        $prefix ??= NamingHelper::acpControllerPrefix($controller);

        \Route::get("{$prefix}/", [$controller, 'index']);
        \Route::get("{$prefix}/{{$param}}", [$controller, 'show']);
        \Route::put("{$prefix}/{{$param}}", [$controller, 'update']);
        \Route::delete("{$prefix}/{{$param}}", [$controller, 'destroy']);
        \Route::get("{$prefix}/{{$param}}/edit", [$controller, 'edit']);
    }

    public static function withoutCreateAndEdit(string $controller, ?string $prefix = null, string $param = 'id'): void
    {
        $prefix ??= NamingHelper::acpControllerPrefix($controller);

        \Route::get("{$prefix}/", [$controller, 'index']);
        \Route::get("{$prefix}/{{$param}}", [$controller, 'show']);
        \Route::delete("{$prefix}/{{$param}}", [$controller, 'destroy']);
    }
}
