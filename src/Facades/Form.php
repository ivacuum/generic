<?php namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

/** @noinspection PhpUndefinedClassInspection */

class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Utilities\Form::class;
    }
}
