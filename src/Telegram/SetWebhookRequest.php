<?php

namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class SetWebhookRequest implements HttpRequest
{
    public function __construct(private string $url, private string|null $secretToken = null)
    {
    }

    public function endpoint(): string
    {
        return 'setWebhook';
    }

    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
            'secret_token' => $this->secretToken,
        ];
    }
}
