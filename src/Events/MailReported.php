<?php namespace Ivacuum\Generic\Events;

use App\Email;
use Illuminate\Queue\SerializesModels;

/**
 * Жалоба на письмо
 */
class MailReported extends Event
{
    use SerializesModels;

    public function __construct(public Email $email)
    {
    }
}
