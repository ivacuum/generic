<?php namespace Ivacuum\Generic\Telegram;

class SendMessageRequest implements RequestInterface
{
    public function __construct(
        private int $chatId,
        private string $text,
        private bool $disableWebPagePreview
    ) {
    }

    public function endpoint(): string
    {
        return 'sendMessage';
    }

    public function httpMethod(): string
    {
        return 'POST';
    }

    public function jsonSerialize()
    {
        return [
            'text' => $this->text,
            'chat_id' => $this->chatId,
            'disable_web_page_preview' => (int) $this->disableWebPagePreview,
        ];
    }
}
