<?php namespace Ivacuum\Generic\Traits;

use Ivacuum\Generic\Scopes\PaginatorScope;

trait Paginator
{
    public static function bootPaginator()
    {
        static::addGlobalScope(new PaginatorScope);
    }
}
