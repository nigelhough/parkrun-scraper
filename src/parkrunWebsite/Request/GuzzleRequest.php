<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

use GuzzleHttp\Client;
use parkrunScraper\parkrunWebsite\Request\Logger\RequestLoggerInterface;
use TimeSource\TimeSourceInterface;

/**
 * An implementation of a website request with Guzzle.
 */
class GuzzleRequest implements RequestInterface
{
    public function __construct(
        private RequestLoggerInterface $requestLogger,
        private TimeSourceInterface $timeSource,
        private Client $client,
        private string $userAgent
    ) {
    }

    public function getEventSummary(string $location, string $event): string|\Stringable
    {
        $url = "https://www.parkrun.org.uk/{$location}/results/{$event}/";

        $res = $this->client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'User-Agent' => $this->userAgent,
                ],
            ]
        );

        $this->requestLogger->log("{$location}-{$event}", $this->timeSource->now(), ['url' => $url]);

        if ($res->getStatusCode() !== 200) {
            throw new FailedWebsiteRequestException('Unable to read parkrun website');
        }

        return $res->getBody();
    }
}
