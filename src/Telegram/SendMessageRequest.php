<?php namespace Ivacuum\Generic\Telegram;

class SendMessageRequest implements RequestInterface
{
    private int $chatId;
    private bool $disableWebPagePreview;
    private string $text;

    public function __construct(int $chatId, string $text, bool $disableWebPagePreview)
    {
        $this->text = $text;
        $this->chatId = $chatId;
        $this->disableWebPagePreview = $disableWebPagePreview;
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
