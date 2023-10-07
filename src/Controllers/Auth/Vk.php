<?php

namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Auth\SignIn;
use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Events\ExternalIdentityFirstLogin;
use Ivacuum\Generic\Events\ExternalIdentityLogin;
use Ivacuum\Generic\Events\ExternalIdentityLoginError;
use Ivacuum\Generic\Socialite\VkProvider;

class Vk extends Base
{
    protected $provider = 'vk';

    public function index()
    {
        $revoke = request('revoke');

        /** @var VkProvider $driver */
        $driver = \Socialite::driver('vk');

        if ($revoke) {
            $driver = $driver->revoke();
        }

        $this->saveUrlIntended();

        return $driver->redirect();
    }

    public function callback()
    {
        $error = request('error');

        if ($error) {
            event(new ExternalIdentityLoginError($this->provider, request()));

            return redirect(path([SignIn::class, 'index']));
        }

        /** @var \Laravel\Socialite\Two\User $userdata */
        $userdata = \Socialite::driver('vk')->user();
        $identity = $this->externalIdentity($userdata);

        if ($identity->user_id) {
            \Auth::loginUsingId($identity->user_id);

            event(new ExternalIdentityLogin($identity));
            event(new \Ivacuum\Generic\Events\Stats\UserSignedInWithExternalIdentity);

            return redirect()->intended();
        }

        if ($userdata->getEmail() === null) {
            return redirect(path([SignIn::class, 'index']))->with('message', $this->noEmailMessage());
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

    protected function noEmailMessage(): HtmlString
    {
        return new HtmlString('<div>Мы не можем вас зарегистрировать, так как не получили от ВК вашу электронную почту. Доступ к ее адресу можно разрешить при <a class="link" href="' . path([static::class, 'index'], ['revoke' => 1]) . '">повторной попытке</a></div>');
    }
}
