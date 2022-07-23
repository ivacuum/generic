<?php namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class EditMessageReplyMarkupRequest implements HttpRequest
{
    public function __construct(
        private int $chatId,
        private int $messageId,
        private InlineKeyboardMarkup|null $replyMarkup = null
    ) {
    }

    public function endpoint(): string
    {
        return 'editMessageReplyMarkup';
    }

    public function jsonSerialize()
    {
        return [
            'chat_id' => $this->chatId,
            'message_id' => $this->messageId,
            'reply_markup' => $this->replyMarkup,
        ];
    }
}
