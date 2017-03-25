<?php namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    abstract public function breadcrumb();
}
