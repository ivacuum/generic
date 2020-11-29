<?php namespace Ivacuum\Generic\Services;

use Ivacuum\Generic\Jobs\SendTelegramMessageJob;

class Telegram
{
    public function notifyAdmin(string $text): void
    {
        if (\App::isLocal()) {
            $text = "\xF0\x9F\x9A\xA7 local\n{$text}";
        }

        SendTelegramMessageJob::dispatch(config('cfg.telegram.admin_id'), $text, true);
    }

    public function notifyAdminProduction(string $text): void
    {
        if (!\App::isProduction()) {
            return;
        }

        $this->notifyAdmin($text);
    }
}
