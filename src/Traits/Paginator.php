<?php

namespace Ivacuum\Generic\Traits;

use Ivacuum\Generic\Scopes\PaginatorScope;

trait Paginator
{
    protected static function bootPaginator()
    {
        static::addGlobalScope(new PaginatorScope);
    }
}
