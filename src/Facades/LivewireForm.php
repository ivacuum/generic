<?php

namespace Ivacuum\Generic\Facades;

use Illuminate\Support\Facades\Facade;

class LivewireForm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ivacuum\Generic\Utilities\LivewireForm::class;
    }
}
