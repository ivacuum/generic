<?php namespace Ivacuum\Generic\Controllers;

use App\Email;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Carbon;
use Ivacuum\Generic\Events\UserAutologinWithEmailLink;

class Mail extends Controller
{
    public function click(Guard $auth, string $timestamp, int $id, ?string $token = null)
    {
        $goto = request('goto', '/');
        $email = Email::find($id);
        $timestamp = Carbon::createFromFormat(Email::TIMESTAMP_FORMAT, $timestamp);

        if (is_null($email)) {
            return redirect($goto);
        }

        if ($email->created_at->eq($timestamp)) {
            $email->increment('clicks');
        }

        if ($token && $token === $email->token && $email->user_id) {
            /* @var User $user */
            $user = User::find($email->user_id);

            if (!is_null($user)) {
                $user->activate();

                if ($user->status === User::STATUS_ACTIVE) {
                    event(new UserAutologinWithEmailLink($email));

                    $auth->login($user);

                    event(new \Ivacuum\Generic\Events\Stats\UserAutologinWithEmailLink);
                }
            }
        }

        event(new \Ivacuum\Generic\Events\Stats\MailClicked);

        return redirect($goto);
    }

    public function view(string $timestamp, int $id)
    {
        $email = Email::find($id);
        $timestamp = Carbon::createFromFormat(Email::TIMESTAMP_FORMAT, $timestamp);

        if (!is_null($email) && $email->created_at->eq($timestamp)) {
            event(new \Ivacuum\Generic\Events\Stats\MailViewed($email->id));
        }

        return response('', 204);
    }
}
