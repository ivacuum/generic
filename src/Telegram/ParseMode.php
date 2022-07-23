<?php

namespace Ivacuum\Generic\Telegram;

enum ParseMode: string
{
    case Html = 'html';
    case Markdown = 'MarkdownV2';
}
