<?php

declare(strict_types=1);

namespace parkrunScraper\Event;

use parkrunScraper\EventInterface;
use parkrunScraper\ResultCollectionInterface;
use PHPUnit\Framework\TestCase;

class EventSummaryTest extends TestCase
{
    public function testAccessors(): void
    {
        $eventSummary = new EventSummary(
            $event = $this->createMock(EventInterface::class),
            $results = $this->createMock(ResultCollectionInterface::class)
        );
        $this->assertSame($event, $eventSummary->getEvent());
        $this->assertSame($results, $eventSummary->getResults());
    }
}
