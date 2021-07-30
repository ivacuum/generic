<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

class UrlHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Utilities\UrlHelper::class;
    }
}
