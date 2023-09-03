<?php

namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\PasswordBroker;

class ForgotPassword extends Controller
{
    public function index()
    {
        return view('auth.password_remind');
    }

    public function sendResetLink(PasswordBroker $broker)
    {
        $data = request()->validate(['email' => 'required|email']);

        $response = $broker->sendResetLink(['email' => $data['email']]);

        return $response === PasswordBroker::RESET_LINK_SENT
            ? $this->sendOkResponse($response, $data['email'])
            : $this->sendFailedResponse($response);
    }

    protected function sendFailedResponse(string $response)
    {
        return back()->withErrors(['email' => __($response)]);
    }

    protected function sendOkResponse(string $response, string $email)
    {
        event(new \Ivacuum\Generic\Events\Stats\UserPasswordReminded);

        return back()->with('message', __($response, ['email' => $email]));
    }
}
