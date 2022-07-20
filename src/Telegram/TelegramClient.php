<?php namespace Ivacuum\Generic\Telegram;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Client\Factory;
use Ivacuum\Generic\Http\HttpRequest;

class TelegramClient
{
    private int $chatId;

    public function __construct(private Factory $http)
    {
    }

    public function chat(int $chatId)
    {
        $telegram = clone $this;
        $telegram->chatId = $chatId;

        return $telegram;
    }

    public function sendMessage(string $text, bool $disableWebPagePreview = null)
    {
        $request = new SendMessageRequest(
            $this->chatId,
            $text,
            $disableWebPagePreview ?? $this->disableWebPagePreview()
        );

        return new TelegramResponse($this->send($request));
    }

    public function setWebhook(string $url)
    {
        $request = new SetWebhookRequest($url);

        return new TelegramResponse($this->send($request));
    }

    private function configureClient()
    {
        $botToken = config('cfg.telegram.bot_token');

        return $this->http
            ->baseUrl("https://api.telegram.org/bot{$botToken}/")
            ->timeout(15);
    }

    private function disableWebPagePreview(): ?bool
    {
        return config('cfg.telegram.disable_web_page_preview');
    }

    private function payload(HttpRequest $request)
    {
        return collect($request->jsonSerialize())
            ->reject(fn ($value) => $value === null)
            ->all();
    }

    private function send(HttpRequest $request)
    {
        try {
            return $this->configureClient()
                ->post($request->endpoint(), $this->payload($request));
        } catch (ClientException $e) {
            throw TelegramException::errorResponse($e);
        } catch (\Throwable $e) {
            throw TelegramException::generalError($e);
        }
    }
}
