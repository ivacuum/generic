<?php namespace Ivacuum\Generic\Controllers\Acp;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseController extends Controller
{
    protected function alwaysCallBefore(...$parameters)
    {
        $this->populateBreadcrumbsPrefix();
        $this->populateBreadcrumbs(...$parameters);
        $this->beforeCallAction(...$parameters);
    }

    protected function beforeCallAction(...$parameters)
    {
        $method = "{$this->method}Before";

        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $parameters);
        }
    }

    protected function breadcrumbsCreate()
    {
        \Breadcrumbs::push(trans($this->view));
    }

    protected function breadcrumbsCurrentSubpage(Model $model)
    {
        \Breadcrumbs::push(
            $model->breadcrumb(),
            str_replace('.', '/', $this->prefix)."/{$model->getRouteKey()}"
        );

        \Breadcrumbs::push(trans($this->view));
    }

    protected function breadcrumbsShow(Model $model)
    {
        \Breadcrumbs::push($model->breadcrumb());
    }

    protected function populateBreadcrumbs(...$parameters)
    {
        /* Сборка цепочки навигации */
        $method = 'breadcrumbs' . Str::ucfirst($this->method);

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

    protected function redirectAfterDestroy()
    {
        return [
            'status' => 'OK',
            'redirect' => action("{$this->class}@index"),
        ];
    }

    protected function redirectAfterStore($model)
    {
        return redirect()->action("{$this->class}@index");
    }

    protected function redirectAfterUpdate(Model $model, $method = 'index')
    {
        $goto = $this->request->input('goto', '');

        if ($this->request->exists('_save')) {
            return $goto
                ? redirect()->action("{$this->class}@edit", [$model, 'goto' => $goto])
                : redirect()->action("{$this->class}@edit", $model);
        }

        return $goto ? redirect($goto) : redirect()->action("{$this->class}@{$method}");
    }
}
