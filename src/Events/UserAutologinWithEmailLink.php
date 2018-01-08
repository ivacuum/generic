<?php namespace Ivacuum\Generic\Events;

use App\Email;
use Illuminate\Queue\SerializesModels;

/**
 * Автовход по ссылке из письма
 *
 * @property \App\Email $email
 */
class UserAutologinWithEmailLink extends Event
{
    use SerializesModels;

    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }
}
