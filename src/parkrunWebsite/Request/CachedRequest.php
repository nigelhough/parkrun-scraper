<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

use parkrunScraper\Calendar\CalendarInterface;
use parkrunScraper\EventInterface;
use parkrunScraper\parkrunWebsite\Parser\ParserInterface;
use Psr\SimpleCache\CacheInterface;
use TimeSource\TimeSourceInterface;

/**
 * An implementation of a request utilising a cache.
 */
class CachedRequest implements RequestInterface
{
    public function __construct(
        private CacheInterface $cache,
        private RequestInterface $fallback,
        private ParserInterface $parser,
        private CalendarInterface $calendar,
        private TimeSourceInterface $timeSource
    ) {
    }

    public function getEventSummary(string $location, string $event): string|\Stringable
    {
        $cacheKey = "{$location}-{$event}";

        if ($this->cache->has($cacheKey)) {
            $cachedSummary = (string) $this->cache->get($cacheKey, '');
            if ($event !== EventInterface::LATEST_EVENT) {
                return $cachedSummary;
            }

            // Latest Results may be an old cache.
            $parsedEvent = $this->parser->parseEvent($cachedSummary);

            $isParkrunDay = $this->calendar->isParkrunDay($location, $this->timeSource->today());
            $lastEventCached = (
                $parsedEvent->getDate() == $this->calendar->lastParkrunDay(
                    $location,
                    $this->timeSource->today()
                )
            );
            $desiredCacheDate = $isParkrunDay ? $this->timeSource->today() : $this->calendar->lastParkrunDay(
                $location,
                $this->timeSource->today()
            );
            $outDatedCache = $parsedEvent->getDate() < $desiredCacheDate;

            // Return cache, if the cache is up-to-date or, we cached the last event and today is not a parkrun day
            // (we don't expect new results on a non-parkrun day)
            if (!$outDatedCache || ($lastEventCached && !$isParkrunDay)) {
                return $cachedSummary;
            }
        }

        // Get Summary from fallback.
        $summary = $this->fallback->getEventSummary($location, $event);

        // Ensure summary can be parsed.
        $parsedEvent = $this->parser->parseEvent((string) $summary);

        // Cache!
        $this->cache->set($cacheKey, $summary);
        // If latest event, also cache by event number.
        if ($event !== EventInterface::LATEST_EVENT) {
            $eventNoCacheKey = "{$location}-{$parsedEvent->getNumber()}";
            $this->cache->set($eventNoCacheKey, $summary);
        }
        return $summary;
    }
}
