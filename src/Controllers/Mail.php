<?php namespace Ivacuum\Generic\Controllers;

use App\Email;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Ivacuum\Generic\Events\MailReported;
use Ivacuum\Generic\Events\UserAutologinWithEmailLink;

class Mail extends Controller
{
    public function click(Guard $auth, string $timestamp, int $id)
    {
        $goto = request('goto', '/');
        $email = Email::find($id);

        if (null === $email || !\URL::hasValidSignature(request())) {
            return redirect($goto);
        }

        if ($email->hasValidTimestamp($timestamp)) {
            $email->incrementClicks();
        }

        if ($email->user_id) {
            /** @var User $user */
            if (null !== $user = User::find($email->user_id)) {
                $user->activate();

                if ($user->status === User::STATUS_ACTIVE && $auth->id() !== $user->id) {
                    event(new UserAutologinWithEmailLink($email));

                    $auth->login($user);

                    event(new \Ivacuum\Generic\Events\Stats\UserAutologinWithEmailLink);
                }
            }
        }

        event(new \Ivacuum\Generic\Events\Stats\MailClicked);

        return redirect($goto);
    }

    public function report(string $timestamp, int $id)
    {
        /** @var User $user */
        $user = request()->user();

        /** @var Email $email */
        $email = Email::findOrFail($id);

        abort_if(!$email->hasValidTimestamp($timestamp) || $email->user_id !== $user->id, 404);

        event(new MailReported($email));

        return redirect(path(HomeController::class))
            ->with('message', trans('mail.report_thanks'));
    }

    public function view(string $timestamp, int $id)
    {
        $email = Email::find($id);

        if (null !== $email && $email->hasValidTimestamp($timestamp)) {
            event(new \Ivacuum\Generic\Events\Stats\MailViewed($email->id));
        }

        return response()->noContent();
    }
}
