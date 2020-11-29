<?php namespace Ivacuum\Generic\Telegram;

use GuzzleHttp\Exception\ClientException;
use Ivacuum\Generic\Http\GuzzleClientFactory;

class TelegramClient
{
    private $client;

    public function __construct(GuzzleClientFactory $clientFactory)
    {
        $botToken = config('cfg.telegram.bot_token');

        $this->client = $clientFactory
            ->baseUri("https://api.telegram.org/bot{$botToken}/")
            ->timeout(10)
            ->withLog('telegram')
            ->create();
    }

    public function sendMessage(int $chatId, string $text, bool $disableWebPagePreview = true)
    {
        $request = new SendMessageRequest($chatId, $text, $disableWebPagePreview);

        return new SendMessageResponse($this->send($request));
    }

    private function send(RequestInterface $request)
    {
        try {
            return $this->client->request(
                $request->httpMethod(),
                $request->endpoint(),
                [
                    'json' => $request->jsonSerialize(),
                ]
            );
        } catch (ClientException $e) {
            throw TelegramException::errorResponse($e);
        } catch (\Throwable $e) {
            throw TelegramException::generalError($e);
        }
    }
}
