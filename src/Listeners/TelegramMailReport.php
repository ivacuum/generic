<?php namespace Ivacuum\Generic\Listeners;

use Ivacuum\Generic\Events\MailReported;

class TelegramMailReport extends TelegramNotifier
{
    public function handle(MailReported $event)
    {
        $email = $event->email;

        $text = "Жалоба на письмо {$email->id} от {$email->to}";

        $this->telegram->notifyAdmin($text);
    }
}
