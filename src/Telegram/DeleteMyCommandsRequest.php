<?php namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class DeleteMyCommandsRequest implements HttpRequest
{
    public function __construct(private ?LanguageCode $languageCode = null)
    {
    }

    public function endpoint(): string
    {
        return 'deleteMyCommands';
    }

    public function jsonSerialize(): array
    {
        return [
            'language_code' => $this->languageCode?->value,
        ];
    }
}
