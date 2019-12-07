<?php namespace Ivacuum\Generic\Controllers\Auth;

use App\ExternalIdentity;
use App\Http\Controllers\Controller;
use App\User;
use Ivacuum\Generic\Events\ExternalIdentitySaved;

abstract class Base extends Controller
{
    protected $user;
    protected $provider;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('guest');
    }

    /**
     * Поиск или создание новой учетки социального сервиса
     *
     * @param  \Laravel\Socialite\AbstractUser $user
     *
     * @return \App\ExternalIdentity
     */
    protected function externalIdentity($user)
    {
        $identity = $this->findIdentityByUid($user->id);

        if (null === $identity) {
            $identity = $this->saveExternalIdentity($user);
        } else {
            $identity->touch();
        }

        return $identity;
    }

    /**
     * Поиск учетки по ID в социальном сервисе
     *
     * @param  string $uid
     *
     * @return \App\ExternalIdentity
     */
    protected function findIdentityByUid($uid)
    {
        /** @var \App\ExternalIdentity $model */
        $model = ExternalIdentity::where('uid', $uid)
            ->where('provider', $this->provider)
            ->first();

        return $model;
    }

    /**
     * Поиск пользователя сайта по электронной почте
     *
     * @param  string $email
     *
     * @return \App\User
     */
    protected function findUserByEmail($email)
    {
        /** @var \App\User $user */
        $user = $this->user->where('email', $email)->first();

        return $user;
    }

    /**
     * Мгновенная регистрация пользователя
     *
     * @param  \Laravel\Socialite\AbstractUser $user
     *
     * @return \App\User
     */
    protected function registerUser($user)
    {
        event(new \Ivacuum\Generic\Events\Stats\UserRegisteredWithExternalIdentity);

        return $this->user->create([
            'email'  => $user->email,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Сохранение поступивших от социального сервиса данных
     *
     * @param  \Laravel\Socialite\AbstractUser $user
     *
     * @return \App\ExternalIdentity
     */
    protected function saveExternalIdentity($user)
    {
        event(new ExternalIdentitySaved($user));
        event(new \Ivacuum\Generic\Events\Stats\ExternalIdentityAdded);

        $externalIdentity = new ExternalIdentity;
        $externalIdentity->uid = $user->id;
        $externalIdentity->email = (string) $user->email;
        $externalIdentity->provider = $this->provider;
        $externalIdentity->save();

        return $externalIdentity;
    }

    /**
     * Сохранение адреса для перенаправления после входа
     *
     * @return bool
     */
    protected function saveUrlIntended()
    {
        $goto = request('goto');

        if ($goto) {
            \Redirect::setIntendedUrl($goto);
        }

        return true;
    }
}
