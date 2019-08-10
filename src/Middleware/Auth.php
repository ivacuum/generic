<?php namespace Ivacuum\Generic\Middleware;

use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class Auth extends Authenticate
{
    /**
     * Обработка входящего запроса
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->checkStatus($this->authenticate($request, $guards));

        return $next($request);
    }

    /**
     * Проверка активирован ли пользователь
     *
     * @param  \App\User $user
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function checkStatus($user): void
    {
        if ($user !== null && !$this->isUserActive($user)) {
            \Auth::logout();

            throw new AuthenticationException('Not active.');
        }
    }

    protected function isUserActive(User $user): bool
    {
        return (int) $user->status === User::STATUS_ACTIVE;
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return action('Auth\SignIn@login');
        }
    }
}
