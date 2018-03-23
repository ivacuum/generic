<?php namespace Ivacuum\Generic\Events;

use App\Email;
use Illuminate\Queue\SerializesModels;

/**
 * Жалоба на письмо
 *
 * @property \App\Email $email
 */
class MailReported extends Event
{
    use SerializesModels;

    public $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }
}
