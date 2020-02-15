<?php namespace Ivacuum\Generic\Jobs;

use Telegram\Bot\Api;

class SendTelegramMessageJob extends BaseJob
{
    public $tries = 10;
    public $timeout = 5;
    public $retryAfter = 30;

    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function handle(Api $telegram)
    {
        $telegram->sendMessage($this->params);

        event(new \Ivacuum\Generic\Events\Stats\TelegramSent);
    }
}
