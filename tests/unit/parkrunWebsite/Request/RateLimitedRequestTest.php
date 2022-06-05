<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

use parkrunScraper\parkrunWebsite\Request\Logger\RequestLoggerInterface;
use PHPUnit\Framework\TestCase;
use TimeSource\TimeSourceInterface;

class RateLimitedRequestTest extends TestCase
{
    public function testGracePeriod(): void
    {
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:55'));

        $fallback = $this->createMock(RequestInterface::class);
        $fallback->method('getEventSummary')->willReturn('fallback-summary');

        $timeSource = $this->createMock(TimeSourceInterface::class);
        $timeSource->method('now')->willReturn(new \DateTimeImmutable('2022-06-10 12:34:56'));

        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        // Grace not exceeded.
        $this->assertSame(
            'fallback-summary',
            $rateLimitedRequest->getEventSummary('location', '1')
        );

        // Exceed Grace.
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:59'));
        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        $this->expectException(RequestGracePeriodExceededException::class);
        $rateLimitedRequest->getEventSummary('location', '1');
    }

    public function testRequestLimit(): void
    {
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:55'));
        $requestLogger->method('getRequests')->willReturn(
            (function (): \Generator {
                yield ['key' => 'location-1', 'timestamp' => 1654177124, 'context' => ['url' => '']];
            })()
        );

        $fallback = $this->createMock(RequestInterface::class);
        $fallback->method('getEventSummary')->willReturn('fallback-summary');

        $timeSource = $this->createMock(TimeSourceInterface::class);
        $timeSource->method('now')->willReturn(new \DateTimeImmutable('2022-06-10 12:34:56'));

        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        // Limit not exceeded.
        $this->assertSame(
            'fallback-summary',
            $rateLimitedRequest->getEventSummary('location', '1')
        );

        // Exceed Limit.
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:55'));
        $requestLogger->method('getRequests')->willReturn(
            (function (): \Generator {
                for ($i = 0; $i < 21; $i++) {
                    yield [
                        'key' => 'location-' . rand(0, 1000),
                        'timestamp' => (new \DateTimeImmutable('2022-06-10 12:33:00'))->getTimestamp(),
                        'context' => ['url' => ''],
                    ];
                }
            })()
        );
        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        $this->expectException(RequestLimitExceededException::class);
        $rateLimitedRequest->getEventSummary('location', '1');
    }

    public function testUniqueRequestLimit(): void
    {
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:55'));
        $requestLogger->method('getRequests')->willReturn(
            (function (): \Generator {
                yield ['key' => 'location-1', 'timestamp' => 1654177124, 'context' => ['url' => '']];
            })()
        );

        $fallback = $this->createMock(RequestInterface::class);
        $fallback->method('getEventSummary')->willReturn('fallback-summary');

        $timeSource = $this->createMock(TimeSourceInterface::class);
        $timeSource->method('now')->willReturn(new \DateTimeImmutable('2022-06-10 12:34:56'));

        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        // Limit not exceeded.
        $this->assertSame(
            'fallback-summary',
            $rateLimitedRequest->getEventSummary('location', '1')
        );

        // Exceed Limit.
        $requestLogger = $this->createMock(RequestLoggerInterface::class);
        $requestLogger->method('getLastRequestTime')->willReturn(new \DateTimeImmutable('2022-06-10 12:33:55'));
        $requestLogger->method('getRequests')->willReturn(
            (function (): \Generator {
                for ($i = 0; $i < 3; $i++) {
                    yield [
                        'key' => 'location-1',
                        'timestamp' => (new \DateTimeImmutable('2022-06-10 12:33:00'))->getTimestamp(),
                        'context' => ['url' => ''],
                    ];
                }
            })()
        );
        $rateLimitedRequest = new RateLimitedRequest(
            $requestLogger,
            $timeSource,
            $fallback
        );

        $this->expectException(RequestLimitExceededException::class);
        $rateLimitedRequest->getEventSummary('location', '1');
    }
}
