<?php namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class SignIn extends Controller
{
    use ThrottlesLogins;

    protected $username = 'email';

    public function index()
    {
        if ($goto = request('goto')) {
            \Redirect::setIntendedUrl($goto);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);

            return null;
        }

        if ($this->attemptLogin($request)) {
            $this->loginOkCallback();

            return $this->sendOkResponse($request);
        }

        $username = $this->username();

        if ($this->attemptLoginCustom($request)) {
            $this->loginCustomOkCallback();

            return $this->sendOkResponse($request);
        }

        $this->username = $username;

        $this->incrementLoginAttempts($request);

        return $this->sendFailedResponse($request);
    }

    public function logout()
    {
        \Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        event(new \Ivacuum\Generic\Events\Stats\UserLoggedOut);

        return $this->sendLoggedOutResponse();
    }

    protected function attemptLogin(Request $request)
    {
        return \Auth::attempt(
            $this->credentials($request), !$request->filled('foreign')
        );
    }

    protected function attemptLoginCustom(Request $request)
    {
        return false;
    }

    protected function credentials(Request $request)
    {
        return [
            'status' => User::STATUS_ACTIVE,
            'password' => $request->input('password'),
            $this->username() => $request->input('email'),
        ];
    }

    protected function loginOkCallback()
    {
        event(new \Ivacuum\Generic\Events\Stats\UserSignedInWithEmail);
    }

    protected function loginCustomOkCallback()
    {
    }

    protected function sendAuthenticatedResponse()
    {
        return redirect()->intended(path(HomeController::class));
    }

    protected function sendFailedResponse(Request $request)
    {
        return back()
            ->with('message', __('auth.failed'))
            ->withInput($request->except('password'));
    }

    protected function sendLoggedOutResponse()
    {
        return redirect(path(HomeController::class));
    }

    protected function sendOkResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->sendAuthenticatedResponse();
    }

    protected function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
