<?php namespace Ivacuum\Generic\Middleware;

class Breadcrumbs
{
    public function handle($request, \Closure $next, $trans, $slug = null)
    {
        \Breadcrumbs::push(trans($trans), $slug);

        return $next($request);
    }
}
