<?php

namespace Ivacuum\Generic\Controllers\Auth;

use App\ExternalIdentity;
use App\Http\Controllers\Controller;
use App\User;
use Ivacuum\Generic\Events\ExternalIdentitySaved;
use Laravel\Socialite\AbstractUser;

abstract class Base extends Controller
{
    protected $provider;

    public function __construct(protected User $user)
    {
        $this->middleware('guest');
    }

    /**
     * Поиск или создание новой учетки социального сервиса
     */
    protected function externalIdentity(AbstractUser $user): ExternalIdentity
    {
        $identity = $this->findIdentityByUid($user->getId());

        if ($identity === null) {
            $identity = $this->saveExternalIdentity($user);
        } else {
            $identity->touch();
        }

        return $identity;
    }

    /**
     * Поиск учетки по ID в социальном сервисе
     */
    protected function findIdentityByUid(string $uid): ExternalIdentity|null
    {
        /** @var \App\ExternalIdentity $model */
        $model = ExternalIdentity::where('uid', $uid)
            ->where('provider', $this->provider)
            ->first();

        return $model;
    }

    /**
     * Поиск пользователя сайта по электронной почте
     */
    protected function findUserByEmail(string $email): User|null
    {
        /** @var \App\User $user */
        $user = $this->user->where('email', $email)->first();

        return $user;
    }

    /**
     * Мгновенная регистрация пользователя
     */
    protected function registerUser(AbstractUser $user): User
    {
        event(new \Ivacuum\Generic\Events\Stats\UserRegisteredWithExternalIdentity);

        return $this->user->create([
            'email' => $user->getEmail(),
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Сохранение поступивших от социального сервиса данных
     */
    protected function saveExternalIdentity(AbstractUser $user): ExternalIdentity
    {
        event(new ExternalIdentitySaved($user));
        event(new \Ivacuum\Generic\Events\Stats\ExternalIdentityAdded);

        $externalIdentity = new ExternalIdentity;
        $externalIdentity->uid = $user->getId();
        $externalIdentity->email = (string) $user->getEmail();
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
