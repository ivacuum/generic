<?php

namespace Ivacuum\Generic\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @mixin \Eloquent
 */
abstract class Model extends BaseModel
{
    abstract public function breadcrumb();
}
