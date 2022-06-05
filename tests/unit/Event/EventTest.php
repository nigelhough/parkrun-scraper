<?php

declare(strict_types=1);

namespace parkrunScraper\Event;

use parkrunScraper\LocationInterface;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testAccessors(): void
    {
        $event = new Event(
            $location = $this->createMock(LocationInterface::class),
            1,
            $date = new \DateTimeImmutable('2022-12-06')
        );
        $this->assertSame($location, $event->getLocation());
        $this->assertSame(1, $event->getNumber());
        $this->assertSame($date, $event->getDate());
    }
}
