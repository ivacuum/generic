<?php

namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class SendPhotoRequest implements HttpRequest
{
    public function __construct(
        private int $chatId,
        private string $fileId,
        private string|null $caption,
        private ParseMode|null $parseMode,
        private InlineKeyboardMarkup|null $replyMarkup = null
    ) {
    }

    public function endpoint(): string
    {
        return 'sendPhoto';
    }

    public function jsonSerialize(): array
    {
        return [
            'photo' => $this->fileId,
            'caption' => $this->caption,
            'chat_id' => $this->chatId,
            'parse_mode' => $this->parseMode?->value,
            'reply_markup' => $this->replyMarkup,
        ];
    }
}
