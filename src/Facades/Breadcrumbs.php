<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

/** @noinspection PhpUndefinedClassInspection */

class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Breadcrumbs\Breadcrumbs::class;
    }
}
