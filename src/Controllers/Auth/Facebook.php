<?php namespace Ivacuum\Generic\Controllers\Auth;

use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Events\ExternalIdentityFirstLogin;
use Ivacuum\Generic\Events\ExternalIdentityLogin;
use Ivacuum\Generic\Events\ExternalIdentityLoginError;

class Facebook extends Base
{
    protected $provider = 'facebook';

    public function index()
    {
        $rerequest = request('rerequest');

        $driver = $this->driver();

        if ($rerequest) {
            $driver = $driver->reRequest();
        }

        $this->saveUrlIntended();

        return $driver->redirect();
    }

    public function callback()
    {
        $error = request('error');

        if ($error) {
            event(new ExternalIdentityLoginError($this->provider, request()));

            return redirect(path('Auth@login'));
        }

        /* @var \Laravel\Socialite\Two\User $userdata */
        $userdata = $this->driver()->user();
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
     * @return \Laravel\Socialite\Two\FacebookProvider
     */
    protected function driver()
    {
        return \Socialite::driver('facebook');
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    protected function noEmailMessage()
    {
        return new HtmlString('<div>Мы не можем вас зарегистрировать, так как не получили от Фэйсбука вашу электронную почту. Доступ к ее адресу можно разрешить при <a class="link" href="'.path('Auth\Facebook@index', ['rerequest' => 1]).'">повторной попытке</a></div>');
    }
}
