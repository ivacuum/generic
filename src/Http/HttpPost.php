<?php namespace Ivacuum\Generic\Http;

interface HttpPost extends HttpRequest
{
    public function endpoint(): string;
}
