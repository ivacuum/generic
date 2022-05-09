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
    protected $method;
    protected $prefix;

    public function callAction($method, $parameters)
    {
        $this->fillControllerFields();

        $response = null;

        if (method_exists($this, 'alwaysCallBefore')) {
            $response = $this->alwaysCallBefore(...array_values($parameters));
        }

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

    protected function appendViewSharedVars(): void
    {
        view()->share([
            'tpl' => $this->prefix,
            'view' => $this->view,
            'controller' => static::class,
        ]);
    }

    protected function controllerBasename(): string
    {
        $class = get_class($this);

        $class = str_ends_with($class, 'Controller')
            ? \Str::replaceLast('Controller', '', $class)
            : $class;

        return str_replace('App\Http\Controllers\\', '', $class);
    }

    protected function fillControllerFields(): void
    {
        $action = \Route::currentRouteAction();
        $this->method = str_contains($action, '@')
            ? \Arr::last(explode('@', $action))
            : null;

        $this->prefix = implode('.', array_map(fn ($ary) => \Str::snake($ary, '-'), explode('\\', $this->controllerBasename())));

        $this->view = $this->method
            ? $this->prefix . "." . \Str::snake($this->method)
            : $this->prefix;
    }

    protected function redirectAfterStore($model)
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

        return $goto
            ? redirect($goto)
            : redirect(path([static::class, $method]));
    }
}
