<?php

declare(strict_types=1);

namespace parkrunWebsite\Request;

use parkrunScraper\Calendar\CalendarInterface;
use parkrunScraper\EventInterface;
use parkrunScraper\parkrunWebsite\Parser\ParserInterface;
use parkrunScraper\parkrunWebsite\Request\CachedRequest;
use parkrunScraper\parkrunWebsite\Request\RequestInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use TimeSource\TimeSourceInterface;

class CachedRequestTest extends TestCase
{
    /**
     * @dataProvider dataGetEventSummary
     */
    public function testGetEventSummary(
        string $eventNo,
        string $expected,
        bool $hasCache,
        \DateTimeImmutable $cachedDate,
        bool $isParkrunDay,
        \DateTimeImmutable $lastParkrunDate,
        \DateTimeImmutable $today
    ): void {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('has')->willReturn($hasCache);
        $cache->method('get')->willReturn('cached-summary');
        $fallback = $this->createMock(RequestInterface::class);
        $fallback->method('getEventSummary')->willReturn('fallback-summary');
        $event = $this->createMock(EventInterface::class);
        $event->method('getDate')->willReturn($cachedDate);
        $parser = $this->createMock(ParserInterface::class);
        $parser->method('parseEvent')->willReturn($event);
        $calendar = $this->createMock(CalendarInterface::class);
        $calendar->method('isParkrunDay')->willReturn($isParkrunDay);
        $calendar->method('lastParkrunDay')->willReturn($lastParkrunDate);
        $timeSource = $this->createMock(TimeSourceInterface::class);
        $timeSource->method('today')->willReturn($today);

        $cachedRequest = new CachedRequest(
            $cache,
            $fallback,
            $parser,
            $calendar,
            $timeSource
        );

        $this->assertSame($expected, $cachedRequest->getEventSummary('not-real-event', $eventNo));
    }

    public function dataGetEventSummary(): \Generator
    {
        yield 'No Cache for numbered event: Fallback' => [
            '1',
            'fallback-summary',
            false,
            new \DateTimeImmutable('0000-00-00'),
            true,
            new \DateTimeImmutable('2022-06-04'),
            new \DateTimeImmutable('2022-06-04'),
        ];
        yield 'No Cache for latest event: Fallback' => [
            EventInterface::LATEST_EVENT,
            'fallback-summary',
            false,
            new \DateTimeImmutable('0000-00-00'),
            true,
            new \DateTimeImmutable('2022-06-04'),
            new \DateTimeImmutable('2022-06-04'),
        ];
        yield 'Cache for numbered event: Always return Cache' => [
            '1',
            'cached-summary',
            true,
            new \DateTimeImmutable('0000-00-00'),
            true,
            new \DateTimeImmutable('2022-06-04'),
            new \DateTimeImmutable('2022-06-04'),
        ];
        yield 'Cache for latest event: Cached if cached date is today' => [
            'eventNo' => EventInterface::LATEST_EVENT,
            'expected' => 'cached-summary',
            'hasCache' => true,
            'cachedDate' => new \DateTimeImmutable('2022-06-04'),
            'isParkrunDay' => true,
            'lastParkrunDate' => new \DateTimeImmutable('2022-05-21'),
            'today' => new \DateTimeImmutable('2022-06-04'),
        ];
        yield 'Cache for latest event: Cached if cached date is last parkrun date' => [
            'eventNo' => EventInterface::LATEST_EVENT,
            'expected' => 'cached-summary',
            'hasCache' => true,
            'cachedDate' => new \DateTimeImmutable('2022-06-04'),
            'isParkrunDay' => false,
            'lastParkrunDate' => new \DateTimeImmutable('2022-06-04'),
            'today' => new \DateTimeImmutable('2022-06-17'),
        ];
        yield 'Cache for latest event: Fallback if today is parkrun day and cached is older than today' => [
            'eventNo' => EventInterface::LATEST_EVENT,
            'expected' => 'fallback-summary',
            'hasCache' => true,
            'cachedDate' => new \DateTimeImmutable('2022-05-28'),
            'isParkrunDay' => true,
            'lastParkrunDate' => new \DateTimeImmutable('2022-05-28'),
            'today' => new \DateTimeImmutable('2022-06-18'),
        ];
        yield 'Cache for latest event: Fallback if today is after a parkrun greather than last cache' => [
            'eventNo' => EventInterface::LATEST_EVENT,
            'expected' => 'fallback-summary',
            'hasCache' => true,
            'cachedDate' => new \DateTimeImmutable('2022-05-28'),
            'isParkrunDay' => false,
            'lastParkrunDate' => new \DateTimeImmutable('2022-06-18'),
            'today' => new \DateTimeImmutable('2022-06-19'),
        ];
        yield 'Cache for latest event: Cached if cached is last parkrun and not a parkrun today' => [
            'eventNo' => EventInterface::LATEST_EVENT,
            'expected' => 'cached-summary',
            'hasCache' => true,
            'cachedDate' => new \DateTimeImmutable('2022-06-18'),
            'isParkrunDay' => false,
            'lastParkrunDate' => new \DateTimeImmutable('2022-06-18'),
            'today' => new \DateTimeImmutable('2022-06-19'),
        ];
    }
}
