<?php namespace Ivacuum\Generic\Telegram;

use Ivacuum\Generic\Http\HttpRequest;

class SetMyCommandsRequest implements HttpRequest
{
    /** @param array<BotCommand> $commands */
    public function __construct(private array $commands, private ?LanguageCode $languageCode = null)
    {
    }

    public function endpoint(): string
    {
        return 'setMyCommands';
    }

    public function jsonSerialize(): array
    {
        return [
            'commands' => $this->commands,
            'language_code' => $this->languageCode?->value,
        ];
    }
}
