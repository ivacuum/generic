<?php namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;

class ResetPassword extends Controller
{
    /**
     * Флаг будет установлен в true, если пользователю запрещено
     * восстанавливать пароль (см. userStatusesOkToReset())
     *
     * @var bool
     */
    protected $banned_user = false;

    public function index($token = null)
    {
        abort_unless($token, 404);

        return view('auth.password_reset', compact('token'));
    }

    public function reset(PasswordBroker $broker)
    {
        $credentials = request()->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials['password_confirmation'] = $credentials['password'];

        $response = $broker->reset($credentials, function ($user, $password) {
            /* @var user $user */
            if (in_array($user->status, $this->userStatusesOkToReset())) {
                $this->resetOkCallback($user, $password);
            } else {
                $this->banned_user = true;
            }
        });

        if ($this->banned_user) {
            return $this->sendBannedResponse();
        }

        return $response === PasswordBroker::PASSWORD_RESET
            ? $this->sendOkResponse($response)
            : $this->sendFailedResponse($response);
    }

    protected function redirectPath(): string
    {
        return path('Home@index');
    }

    protected function resetOkCallback(User $user, string $password): void
    {
        $user->activate();

        $user->password = $password;

        $user->setRememberToken(str_random(60));
        $user->save();

        event(new PasswordReset($user));

        \Auth::login($user);
    }

    protected function sendBannedResponse()
    {
        return back()
            ->withInput(request(['email']))
            ->with('message', trans('passwords.banned'));
    }

    protected function sendFailedResponse(string $response)
    {
        return back()
            ->withInput(request(['email']))
            ->withErrors(['email' => trans($response)]);
    }

    protected function sendOkResponse(string $response)
    {
        event(new \Ivacuum\Generic\Events\Stats\UserPasswordResetted);

        return redirect($this->redirectPath())
            ->with('message', trans($response));
    }

    protected function userStatusesOkToReset()
    {
        return [
            User::STATUS_INACTIVE,
            User::STATUS_ACTIVE
        ];
    }
}