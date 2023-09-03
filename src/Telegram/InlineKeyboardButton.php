<?php

namespace Ivacuum\Generic\Telegram;

class InlineKeyboardButton implements \JsonSerializable
{
    public function __construct(
        private string $text,
        private string|null $url = null,
        private string|null $callbackData = null
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
            'text' => $this->text,
            'callback_data' => $this->callbackData,
        ];
    }
}
