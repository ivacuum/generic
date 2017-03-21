<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

class MetricsHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Utilities\MetricsHelper::class;
    }
}
