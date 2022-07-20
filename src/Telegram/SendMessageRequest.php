<?php namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class SendMessageRequest implements HttpRequest
{
    public function __construct(
        private int $chatId,
        private string $text,
        private ?bool $disableWebPagePreview = false
    ) {
    }

    public function endpoint(): string
    {
        return 'sendMessage';
    }

    public function jsonSerialize()
    {
        return [
            'text' => $this->text,
            'chat_id' => $this->chatId,
            'disable_web_page_preview' => $this->disableWebPagePreview
                ? true
                : null,
        ];
    }
}
