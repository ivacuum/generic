<?php namespace Ivacuum\Generic\Http;

use GuzzleHttp\TransferStats;
use Ivacuum\Generic\Events\ExternalHttpRequestMade;

class ExternalHttpRequestTransferStatsDispatcher
{
    public function __construct(private string $serviceName)
    {
    }

    public function __invoke(TransferStats $stats): void
    {
        $request = $stats->getRequest();
        $response = $stats->getResponse();
        $uri = $request->getUri();

        if (!$stats->hasResponse()) {
            return;
        }

        event(new ExternalHttpRequestMade(
            $this->serviceName,
            $request->getMethod(),
            $uri->getScheme(),
            $uri->getHost(),
            $uri->getPath(),
            $uri->getQuery(),
            $request->getHeaders(),
            (string) $request->getBody(),
            $response->getHeaders(),
            (string) $response->getBody(),
            $this->responseSize($stats),
            $stats->getHandlerStat('total_time_us') ?? $stats->getHandlerStat('total_time') * 1_000_000,
            $response->getStatusCode(),
            $stats->getHandlerStat('http_version') ?? '',
            $stats->getHandlerStat('redirect_count') ?? 0,
            $stats->getHandlerStat('redirect_time_us') ?? $stats->getHandlerStat('redirect_time') * 1_000_000,
            $stats->getHandlerStat('redirect_url') ?? ''
        ));
    }

    private function responseSize(TransferStats $stats)
    {
        $responseSize = $stats->getHandlerStat('download_content_length');

        if ($responseSize >= 0) {
            return $responseSize ?? 0;
        }

        return mb_strlen((string) $stats->getResponse()->getBody());
    }
}
