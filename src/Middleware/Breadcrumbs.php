<?php namespace Ivacuum\Generic\Middleware;

class Breadcrumbs
{
    public function handle($request, \Closure $next, $trans, $slug = null)
    {
        \Breadcrumbs::push(__($trans), $slug);

        return $next($request);
    }
}
