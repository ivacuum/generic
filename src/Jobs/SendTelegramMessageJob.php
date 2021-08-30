<?php namespace Ivacuum\Generic\Jobs;

use Ivacuum\Generic\Telegram\TelegramClient;
use Ivacuum\Generic\Telegram\TelegramException;

class SendTelegramMessageJob extends BaseJob
{
    public $tries = 10;
    public $backoff = 30;
    public $timeout = 15;
    public $maxExceptions = 1;

    public function __construct(
        private int $chatId,
        private string $text,
        private bool $disableWebPagePreview
    ) {
    }

    public function handle(TelegramClient $telegram)
    {
        try {
            $telegram->sendMessage($this->chatId, $this->text, $this->disableWebPagePreview);
        } catch (TelegramException $e) {
            $code = $e->getCode();

            if ($code === 413) {
                $text = mb_substr($this->text, 0, 3000);

                $telegram->sendMessage($this->chatId, $text, $this->disableWebPagePreview);

                $text = mb_substr($e->getMessage(), 0, 3000);

                $telegram->sendMessage($this->chatId, $text, $this->disableWebPagePreview);
            } elseif ($code === 429) {
                $this->release(3600);

                return;
            } else {
                throw $e;
            }
        }

        event(new \Ivacuum\Generic\Events\Stats\TelegramSent);
    }
}
