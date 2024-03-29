<?php

namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Auth\SignIn;
use Ivacuum\Generic\Events\ExternalIdentityFirstLogin;
use Ivacuum\Generic\Events\ExternalIdentityLogin;
use Ivacuum\Generic\Events\ExternalIdentityLoginError;

/**
 * Вход через Гугл
 *
 * Настройка сайта: console.developers.google.com
 */
class Google extends Base
{
    protected $provider = 'google';

    public function index()
    {
        $this->saveUrlIntended();

        return \Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $error = request('error');

        if ($error) {
            event(new ExternalIdentityLoginError($this->provider, request()));

            return redirect(path([SignIn::class, 'index']));
        }

        /** @var \Laravel\Socialite\Two\User $userdata */
        $userdata = \Socialite::driver('google')->user();
        $identity = $this->externalIdentity($userdata);

        if ($identity->user_id) {
            \Auth::loginUsingId($identity->user_id);

            event(new ExternalIdentityLogin($identity));
            event(new \Ivacuum\Generic\Events\Stats\UserSignedInWithExternalIdentity);

            return redirect()->intended();
        }

        if ($userdata->getEmail() === null) {
            return redirect(path([SignIn::class, 'index']))
                ->with('message', 'Мы не можем вас зарегистрировать, так как не получили от Гугла вашу электронную почту');
        }

        if (null === $user = $this->findUserByEmail($userdata->getEmail())) {
            $user = $this->registerUser($userdata);
        }

        if (!$identity->user_id) {
            $identity->update(['user_id' => $user->id]);
        }

        $user->activate();

        \Auth::login($user, true);

        event(new ExternalIdentityFirstLogin($identity, $user));

        return redirect()->intended();
    }
}
