<?php namespace Ivacuum\Generic\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class OdnoklassnikiProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['GET_EMAIL'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://connect.ok.ru/oauth/authorize', $state);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getTokenUrl()
    {
        return 'https://api.odnoklassniki.ru/oauth/token.do';
    }

    protected function getUserByToken($token)
    {
        $params = [
            'format' => 'json',
            'method' => 'users.getCurrentUser',
            'fields' => 'uid,name,first_name,last_name,birthday,pic190x190,has_email,email',
            'application_key' => config('services.odnoklassniki.client_public'),
        ];

        ksort($params);

        $params['sig'] = $this->newSignature($token, $params);
        $params['access_token'] = $token;

        $response = $this->getHttpClient()->get(
            'https://api.ok.ru/fb.do',
            ['query' => $params]
        );

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => \Arr::get($user, 'uid'),
            'nickname' => null,
            'name'     => \Arr::get($user, 'name'),
            'email'    => \Arr::get($user, 'email'),
            'avatar'   => \Arr::get($user, 'pic190x190'),
        ]);
    }

    protected function newSignature($token, array $params)
    {
        $_params = array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($params), array_values($params));

        return md5(join('', $_params) . md5($token . $this->clientSecret));
    }
}
