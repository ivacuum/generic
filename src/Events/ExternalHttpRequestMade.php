<?php namespace Ivacuum\Generic\Events;

class ExternalHttpRequestMade extends Event
{
    public function __construct(
        public string $serviceName,
        public string $method,
        public string $scheme,
        public string $host,
        public string $path,
        public string $query,
        public array $requestHeaders,
        public string $requestBody,
        public array $responseHeaders,
        public string $responseBody,
        public int $responseSize,
        public int $totalTimeUs,
        public int $httpCode,
        public string $httpVersion,
        public int $redirectCount,
        public int $redirectTimeUs,
        public string $redirectUrl
    ) {
    }
}
