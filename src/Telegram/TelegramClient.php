<?php namespace Ivacuum\Generic\Telegram;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

class TelegramClient
{
    private PendingRequest $http;

    public function __construct(Factory $http)
    {
        $botToken = config('cfg.telegram.bot_token');

        $this->http = $http
            ->baseUrl("https://api.telegram.org/bot{$botToken}/")
            ->timeout(10);
    }

    public function sendMessage(int $chatId, string $text, bool $disableWebPagePreview = true)
    {
        $request = new SendMessageRequest($chatId, $text, $disableWebPagePreview);

        return new SendMessageResponse($this->send($request));
    }

    private function send(RequestInterface $request)
    {
        try {
            return $this->http->post($request->endpoint(), $request->jsonSerialize());
        } catch (ClientException $e) {
            throw TelegramException::errorResponse($e);
        } catch (\Throwable $e) {
            throw TelegramException::generalError($e);
        }
    }
}
