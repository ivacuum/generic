<?php namespace Ivacuum\Generic\Controllers\Auth;

use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Events\ExternalIdentityFirstLogin;
use Ivacuum\Generic\Events\ExternalIdentityLogin;
use Ivacuum\Generic\Events\ExternalIdentityLoginError;
use Ivacuum\Generic\Socialite\VkProvider;

class Vk extends Base
{
    protected $provider = 'vk';

    public function index(VkProvider $vk)
    {
        $revoke = request('revoke');

        if ($revoke) {
            $vk = $vk->revoke();
        }

        $this->saveUrlIntended();

        return $vk->redirect();
    }

    public function callback(VkProvider $vk)
    {
        $error = request('error');

        if ($error) {
            event(new ExternalIdentityLoginError($this->provider, request()));

            return redirect(path('Auth@login'));
        }

        /* @var $userdata \Laravel\Socialite\Two\User */
        $userdata = $vk->user();
        $identity = $this->externalIdentity($userdata);

        if ($identity->user_id) {
            \Auth::loginUsingId($identity->user_id);

            event(new ExternalIdentityLogin($identity));
            event(new \Ivacuum\Generic\Events\Stats\UserSignedInWithExternalIdentity);

            return redirect()->intended('/');
        }

        if (is_null($userdata->email)) {
            return redirect(path('Auth@login'))->with('message', $this->noEmailMessage());
        }

        if (is_null($user = $this->findUserByEmail($userdata->email))) {
            $user = $this->registerUser($userdata);
        }

        if (!$identity->user_id) {
            $identity->update(['user_id' => $user->id]);
        }

        $user->activate();

        \Auth::login($user, true);

        event(new ExternalIdentityFirstLogin($identity, $user));

        return redirect()->intended('/');
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    protected function noEmailMessage()
    {
        return new HtmlString('<div>Мы не можем вас зарегистрировать, так как не получили от ВК вашу электронную почту. Доступ к ее адресу можно разрешить при <a class="link" href="'.path('Auth\Vk@index', ['revoke' => 1]).'">повторной попытке</a></div>');
    }
}
