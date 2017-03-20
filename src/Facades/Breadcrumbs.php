<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Breadcrumbs\Breadcrumbs::class;
    }
}
