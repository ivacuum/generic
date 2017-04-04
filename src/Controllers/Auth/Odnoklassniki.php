<?php namespace Ivacuum\Generic\Controllers\Auth;

use Illuminate\Support\HtmlString;
use Ivacuum\Generic\Events\ExternalIdentityFirstLogin;
use Ivacuum\Generic\Events\ExternalIdentityLogin;
use Ivacuum\Generic\Events\ExternalIdentityLoginError;
use Ivacuum\Generic\Socialite\OdnoklassnikiProvider;

class Odnoklassniki extends Base
{
    protected $provider = 'odnoklassniki';

    public function index(OdnoklassnikiProvider $odnoklassniki)
    {
        $this->saveUrlIntended();

        return $odnoklassniki->redirect();
    }

    public function callback(OdnoklassnikiProvider $odnoklassniki)
    {
        $error = $this->request->input('error');

        if ($error) {
            event(new ExternalIdentityLoginError($this->provider, $this->request));

            return redirect(path('Auth@login'));
        }

        /* @var $userdata \Laravel\Socialite\Two\User */
        $userdata = $odnoklassniki->user();
        $identity = $this->externalIdentity($userdata);

        if ($identity->user_id) {
            \Auth::loginUsingId($identity->user_id);

            event(new ExternalIdentityLogin($identity));
            event(new \Ivacuum\Generic\Events\Stats\UserSignedInWithExternalIdentity);

            return redirect()->intended('/');
        }

        if (is_null($userdata->email)) {
            $this->request->session()->flash('message', $this->noEmailMessage());

            return redirect(path('Auth@login'));
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
        return new HtmlString('<div>Мы не можем вас зарегистрировать, так как не получили от Одноклассников вашу электронную почту. Выберите другой сервис или зарегистрируйтесь вручную</div>');
    }
}
