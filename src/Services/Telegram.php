<?php namespace Ivacuum\Generic\Services;

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

        $chat_id = config('cfg.telegram.admin_id');
        $disable_web_page_preview = true;

        event(new \Ivacuum\Generic\Events\Stats\TelegramSent);

        if (\App::runningInConsole()) {
            $this->telegram->sendMessage(compact('chat_id', 'text', 'disable_web_page_preview'));
        } else {
            register_shutdown_function(
                [$this->telegram, 'sendMessage'],
                compact('chat_id', 'text', 'disable_web_page_preview')
            );
        }
    }

    public function notifyAdminProduction(string $text): void
    {
        if (\App::environment() !== 'production') {
            return;
        }

        $this->notifyAdmin($text);
    }
}
