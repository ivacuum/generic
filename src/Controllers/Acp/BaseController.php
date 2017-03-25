<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected function alwaysCallBefore(...$parameters)
    {
        $this->populateBreadcrumbsPrefix();
        $this->beforeCallAction(...$parameters);
    }

    protected function beforeCallAction(...$parameters)
    {
        $method = "{$this->method}Before";

        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $parameters);
        }
    }

    /**
     * Наполнение цепочки навигации существующими переводами
     */
    protected function populateBreadcrumbsPrefix()
    {
        array_reduce(explode('.', $this->prefix), function ($url, $part) {
            $url[] = $part;

            $prefix = implode('.', $url);
            $index = "{$prefix}.index";

            if ($index !== $trans = trans($index)) {
                \Breadcrumbs::push($trans, implode('/', $url));
            }

            return $url;
        });
    }
}
