<?php namespace Ivacuum\Generic\Telegram;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Client\Factory;
use Ivacuum\Generic\Action\FilterNullsAction;
use Ivacuum\Generic\Http\HttpRequest;

class TelegramClient
{
    private int $chatId;
    private int|null $replyToMessageId = null;
    private bool|null $disableWebPagePreview;
    private ParseMode|null $parseMode = null;
    private InlineKeyboardMarkup|null $replyMarkup = null;

    public function __construct(private Factory $http, private FilterNullsAction $filterNulls)
    {
        $this->disableWebPagePreview = config('cfg.telegram.disable_web_page_preview');
    }

    public function chat(int $chatId)
    {
        $telegram = clone $this;
        $telegram->chatId = $chatId;

        return $telegram;
    }

    public function disableWebPagePreview(bool $disableWebPagePreview = true)
    {
        $telegram = clone $this;
        $telegram->disableWebPagePreview = $disableWebPagePreview;

        return $telegram;
    }

    public function editMessageReplyMarkup(int $messageId)
    {
        $request = new EditMessageReplyMarkupRequest($this->chatId, $messageId, $this->replyMarkup);

        return new TelegramResponse($this->send($request));
    }

    public function editMessageText(int $messageId, string $text)
    {
        $request = new EditMessageTextRequest(
            $this->chatId,
            $messageId,
            $text,
            $this->disableWebPagePreview
        );

        return new TelegramResponse($this->send($request));
    }

    public function html()
    {
        return $this->parseMode(ParseMode::Html);
    }

    public function markdown()
    {
        return $this->parseMode(ParseMode::Markdown);
    }

    public function parseMode(ParseMode $parseMode)
    {
        $telegram = clone $this;
        $telegram->parseMode = $parseMode;

        return $telegram;
    }

    public function replyMarkup(InlineKeyboardMarkup|null $replyMarkup)
    {
        $telegram = clone $this;
        $telegram->replyMarkup = $replyMarkup;

        return $telegram;
    }

    public function replyToMessageId(int $messageId)
    {
        $telegram = clone $this;
        $telegram->replyToMessageId = $messageId;

        return $telegram;
    }

    public function sendLocation(string $lat, string $lon)
    {
        $request = new SendLocationRequest(
            $this->chatId,
            $lat,
            $lon,
            $this->replyMarkup,
            $this->replyToMessageId
        );

        return new TelegramResponse($this->send($request));
    }

    public function sendMessage(string $text)
    {
        $request = new SendMessageRequest(
            $this->chatId,
            $text,
            $this->disableWebPagePreview,
            $this->replyMarkup
        );

        return new TelegramResponse($this->send($request));
    }

    public function sendPhoto(string $fileId, ?string $caption = null)
    {
        $request = new SendPhotoRequest(
            $this->chatId,
            $fileId,
            $caption,
            $this->parseMode,
            $this->replyMarkup
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

    private function payload(HttpRequest $request)
    {
        $payload = $request->jsonSerialize();

        if (is_array($payload)) {
            return $this->filterNulls->execute($request->jsonSerialize());
        }

        return $payload;
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
