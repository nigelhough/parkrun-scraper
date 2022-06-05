<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

use parkrunScraper\parkrunWebsite\Request\Logger\RequestLoggerInterface;
use TimeSource\TimeSourceInterface;

class RateLimitedRequest implements RequestInterface
{
    /** @var int The number of seconds grace that identical requests must be given */
    private const REQUEST_GRACE_IN_SECONDS = 60;

    /** @var int The range in seconds that will limit requests */
    private const REQUEST_RANGE_IN_SECONDS = 15 * 60;

    /** @var int The number of requests allowed within range */
    private const REQUEST_LIMIT = 20;

    /** @var int The number of unique requests allowed within range */
    private const UNIQUE_REQUEST_LIMIT = 2;

    public function __construct(
        private RequestLoggerInterface $requestLogger,
        private TimeSourceInterface $timeSource,
        private RequestInterface $fallback
    ) {
    }

    public function getEventSummary(string $location, string $event): string|\Stringable
    {
        $requestKey = "{$location}-{$event}";
        $lastRequest = $this->requestLogger->getLastRequestTime($requestKey);
        if ($lastRequest !== null) {
            $secondsSinceLastRequest = $this->timeSource->now()->getTimestamp() - $lastRequest->getTimestamp();
            $exceededGrace = $secondsSinceLastRequest < self::REQUEST_GRACE_IN_SECONDS;
            if ($exceededGrace) {
                throw new RequestGracePeriodExceededException('Grace exceeded');
            }
        }

        $rangeStart = $this->timeSource->now()->getTimestamp() - self::REQUEST_RANGE_IN_SECONDS;
        $requestCount = 0;
        $uniqueRequestCount = 0;
        foreach ($this->requestLogger->getRequests() as $log) {
            if ($log['timestamp'] < $rangeStart) {
                break;
            }
            $requestCount++;
            $uniqueRequestCount += (int) ($log['key'] === $requestKey);
        }

        if ($requestCount > self::REQUEST_LIMIT) {
            throw new RequestLimitExceededException('Request limit exceeded');
        }
        if ($uniqueRequestCount > self::UNIQUE_REQUEST_LIMIT) {
            throw new RequestLimitExceededException('Unique request limit exceeded');
        }

        return $this->fallback->getEventSummary($location, $event);
    }
}
