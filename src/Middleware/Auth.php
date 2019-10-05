<?php namespace Ivacuum\Generic\Middleware;

use App\Http\Controllers\Auth\SignIn;
use App\Http\Controllers\Home;
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
        $this->authenticate($request, $guards);
        $this->checkStatus($request->user(), $guards);

        return $next($request);
    }

    protected function checkStatus(?User $user, ...$guards): void
    {
        if ($user !== null && !$this->isUserActive($user)) {
            \Auth::logout();

            throw new AuthenticationException(
                'Not active.', $guards, action([Home::class, 'index'])
            );
        }
    }

    protected function isUserActive(User $user): bool
    {
        return $user->status === User::STATUS_ACTIVE;
    }

    protected function redirectTo($request)
    {
        return action([SignIn::class, 'login']);
    }
}
