<?php namespace Ivacuum\Generic\Services;

use Ivacuum\Generic\Jobs\SendTelegramMessageJob;
use Telegram\Bot\Api;

class Telegram
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function notifyAdmin(string $text): void
    {
        if (\App::isLocal()) {
            $text = "\xF0\x9F\x9A\xA7 local\n{$text}";
        }

        SendTelegramMessageJob::dispatch([
            'text' => $text,
            'chat_id' => config('cfg.telegram.admin_id'),
            'disable_web_page_preview' => true,
        ]);
    }

    public function notifyAdminProduction(string $text): void
    {
        if (!\App::isProduction()) {
            return;
        }

        $this->notifyAdmin($text);
    }
}
