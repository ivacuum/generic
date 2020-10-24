<?php namespace Ivacuum\Generic\Jobs;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramResponseException;

class SendTelegramMessageJob extends BaseJob
{
    public $tries = 10;
    public $timeout = 5;
    public $retryAfter = 30;
    public $maxExceptions = 1;
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function handle(Api $telegram)
    {
        try {
            $telegram->sendMessage($this->params);
        } catch (TelegramResponseException $e) {
            $httpStatusCode = $e->getHttpStatusCode();

            if ($httpStatusCode === 413) {
                $params = $this->params;
                $params['text'] = mb_substr($params['text'], 0, 3000);

                $telegram->sendMessage($params);

                $params['text'] = mb_substr($e->getMessage(), 0, 3000);

                $telegram->sendMessage($params);
            } elseif ($httpStatusCode === 429) {
                $this->release(3600);

                return;
            }
        }

        event(new \Ivacuum\Generic\Events\Stats\TelegramSent);
    }
}
