<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

class Sphinx extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Services\Sphinx::class;
    }
}
