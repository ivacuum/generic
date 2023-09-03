<?php

namespace Ivacuum\Generic\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PaginatorScope implements Scope
{
    protected $extensions = ['Paginator'];

    public function apply(Builder $builder, Model $model)
    {
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    protected function addPaginator(Builder $builder)
    {
        $builder->macro('paginator', function (Builder $builder, $total, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
            $page = $page ?: Paginator::resolveCurrentPage($pageName);
            $perPage = $perPage ?: $builder->getModel()->getPerPage();

            $results = $total
                ? $builder->forPage($page, $perPage)->get($columns)
                : $builder->getModel()->newCollection();

            return new LengthAwarePaginator($results, $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });
    }
}
