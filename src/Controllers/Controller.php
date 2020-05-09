<?php namespace Ivacuum\Generic\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Ivacuum\Generic\Rules\ConcurrencyControl;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected $view;
    protected $class;
    protected $method;
    protected $prefix;

    public function callAction($method, $parameters)
    {
        $this->fillControllerFields();

        $response = null;

        if (method_exists($this, 'alwaysCallBefore')) {
            $response = $this->alwaysCallBefore(...array_values($parameters));
        }

        $this->appendLocale();
        $this->appendRequestUri();
        $this->appendViewSharedVars();

        $this->appendCustomVars();

        return $response !== null
            ? $response
            : parent::callAction($method, $parameters);
    }

    public function validateArray(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $this->getValidationFactory()
            ->make($data, $rules, $messages, $customAttributes)
            ->validate();

        return request(array_keys($rules));
    }

    protected function appendCustomVars(): void
    {
    }

    protected function appendLocale(): void
    {
        $locale = request()->server->get('LARAVEL_LOCALE');

        $prefferedLocale = \Request::getPreferredLanguage(array_keys(config('cfg.locales')));

        view()->share([
            'locale' => $locale ?: config('app.locale'),
            'localeUri' => $locale ? "/{$locale}" : '',
            'localePreffered' => $prefferedLocale,
        ]);
    }

    protected function appendRequestUri(?string $uri = null): void
    {
        view()->share('requestUri', $uri ?? request()->path());
    }

    protected function appendViewSharedVars(): void
    {
        $browserEnv = new \Ivacuum\Generic\Utilities\EnvironmentForCss(request()->userAgent());
        $firstTimeVisit = null === \Session::previousUrl();

        view()->share([
            'tpl' => $this->prefix,
            'goto' => request('goto'),
            'self' => $this->controllerBasename(),
            'view' => $this->view,
            'isMobile' => $browserEnv->isMobile(),
            'isCrawler' => $browserEnv->isCrawler(),
            'isDesktop' => !$browserEnv->isMobile(),
            'controller' => static::class,
            'cssClasses' => (string) $browserEnv,
            'firstTimeVisit' => $firstTimeVisit,
        ]);
    }

    protected function controllerBasename(): string
    {
        $class = get_class($this);

        $class = \Str::endsWith($class, 'Controller')
            ? \Str::replaceLast('Controller', '', $class)
            : $class;

        return str_replace('App\Http\Controllers\\', '', $class);
    }

    protected function fillControllerFields(): void
    {
        $action = \Route::currentRouteAction();
        $this->method = mb_strpos($action, '@')
            ? \Arr::last(explode('@', $action))
            : null;

        $this->prefix = implode('.', array_map(fn ($ary) => \Str::snake($ary, '-'), explode('\\', $this->controllerBasename())));

        $this->view = $this->method
            ? $this->prefix . "." . \Str::snake($this->method)
            : $this->prefix;
    }

    protected function redirectAfterStore(/** @noinspection PhpUnusedParameterInspection */ $model)
    {
        return request()->expectsJson()
            ? response('', 201, ['Location' => path([static::class, 'index'])])
            : redirect(path([static::class, 'index']));
    }

    protected function redirectAfterUpdate($model, $method = 'index')
    {
        if (request()->expectsJson()) {
            return array_merge(
                ['status' => 'OK'],
                $model->updated_at ? [ConcurrencyControl::FIELD => md5($model->updated_at)] : []
            );
        }

        $goto = request('goto', '');

        if (request()->exists('_save')) {
            return $goto
                ? redirect(path([static::class, 'edit'], [$model, 'goto' => $goto]))
                : redirect(path([static::class, 'edit'], $model));
        }

        return $goto ? redirect($goto) : redirect(path([static::class, $method]));
    }
}
