<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected function alwaysCallBefore(...$parameters)
    {
        $this->populateBreadcrumbsPrefix();

        return $this->beforeCallAction(...$parameters);
    }

    protected function beforeCallAction(...$parameters)
    {
        if ($this->method === 'index' && method_exists($this, 'indexBefore')) {
            return $this->indexBefore();
        }

        return null;
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

            if ($index !== $trans = __($index)) {
                \Breadcrumbs::push($trans, implode('/', $url));
            }

            return $url;
        });
    }
}
