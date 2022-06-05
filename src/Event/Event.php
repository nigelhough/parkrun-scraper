<?php

declare(strict_types=1);

namespace parkrunScraper\Event;

use parkrunScraper\EventInterface;
use parkrunScraper\LocationInterface;

/**
 * A parkrun event, a Saturday morning run starting at 09:00.
 */
class Event implements EventInterface
{
    public function __construct(
        private LocationInterface $location,
        private int $number,
        private \DateTimeInterface $date
    ) {
    }

    public function getLocation(): LocationInterface
    {
        return $this->location;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }
}
