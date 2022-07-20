<?php namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class SetWebhookRequest implements HttpRequest
{
    public function __construct(private string $url)
    {
    }

    public function endpoint(): string
    {
        return 'setWebhook';
    }

    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
        ];
    }
}
