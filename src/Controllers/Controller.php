<?php namespace Ivacuum\Generic\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    protected $class;
    protected $method;
    protected $prefix;
    protected $view;

    public function __construct()
    {
        $this->request = request();

        $this->class = str_replace('App\Http\Controllers\\', '', get_class($this));
        $this->method = array_last(explode('@', \Route::currentRouteAction()));

        $this->prefix = implode('.', array_map(function ($ary) {
            return snake_case($ary, '-');
        }, explode('\\', $this->class)));

        $this->view = $this->prefix.".".snake_case($this->method);

        $this->appendBreadcrumbs();
    }

    public function callAction($method, $parameters)
    {
        if (method_exists($this, 'alwaysCallBefore')) {
            call_user_func_array([$this, 'alwaysCallBefore'], $parameters);
        }

        $this->appendLocale();
        $this->appendRequestUri();
        $this->appendViewSharedVars();

        $this->appendCustomVars();

        return parent::callAction($method, $parameters);
    }

    public function validateArray(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException(request(), $validator);
        }
    }

    protected function appendBreadcrumbs()
    {
    }

    protected function appendCustomVars()
    {
    }

    protected function appendLocale()
    {
        $locale = request()->server->get('LARAVEL_LOCALE');

        $preffered_locale = \Request::getPreferredLanguage(array_keys(config('cfg.locales')));

        view()->share([
            'locale' => $locale ?: config('app.locale'),
            'locale_uri' => $locale ? "/{$locale}" : '',
            'locale_preffered' => $preffered_locale,
        ]);
    }

    protected function appendRequestUri($uri = null)
    {
        view()->share('request_uri', $uri ?? request()->path());
    }

    protected function appendViewSharedVars()
    {
        $first_time_visit = is_null(\Session::previousUrl());

        view()->share([
            'tpl' => $this->prefix,
            'goto' => request('goto'),
            'self' => $this->class,
            'view' => $this->view,
            'first_time_visit' => $first_time_visit,
        ]);
    }

    protected function redirectAfterStore($model)
    {
        return redirect(path("{$this->class}@index"));
    }

    protected function redirectAfterUpdate($model, $method = 'index')
    {
        $goto = request('goto', '');

        if (request()->exists('_save')) {
            return $goto
                ? redirect(path("{$this->class}@edit", [$model, 'goto' => $goto]))
                : redirect(path("{$this->class}@edit", $model));
        }

        return $goto ? redirect($goto) : redirect(path("{$this->class}@{$method}"));
    }
}
