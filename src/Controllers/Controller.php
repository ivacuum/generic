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
            return str_replace('_', '-', snake_case($ary));
        }, explode('\\', $this->class)));

        $this->view = $this->prefix.".".snake_case($this->method);
    }

    public function callAction($method, $parameters)
    {
        if (method_exists($this, 'alwaysCallBefore')) {
            call_user_func_array([$this, 'alwaysCallBefore'], $parameters);
        }

        $this->appendLocaleAndUri();
        $this->appendViewSharedVars();

        return parent::callAction($method, $parameters);
    }

    protected function appendLocaleAndUri()
    {
        $locale = $this->request->segment(1);
        $locales = config('cfg.locales');

        if (is_array($locales) && in_array($locale, array_keys($locales))) {
            $request_uri = implode('/', array_slice($this->request->segments(), 1));
        } else {
            $request_uri = $this->request->path();
        }

        $this->appendRequestUri($request_uri);

        $locale = \App::getLocale();

        view()->share([
            'locale' => $locale,
            'locale_uri' => $locale === config('app.locale') ? '' : "/{$locale}",
        ]);
    }

    protected function appendRequestUri($uri = null)
    {
        view()->share('request_uri', $uri ?? $this->request->path());
    }

    protected function appendViewSharedVars()
    {
        view()->share([
            'tpl' => $this->prefix,
            'goto' => $this->request->input('goto'),
            'self' => $this->class,
            'view' => $this->view,
        ]);
    }
}
