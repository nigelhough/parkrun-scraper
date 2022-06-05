<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\Athlete\UnknownAthlete;
use parkrunScraper\AthleteInterface;
use parkrunScraper\EventInterface;
use parkrunScraper\Result\Time\NoTime;
use parkrunScraper\ResultInterface;
use parkrunScraper\TimeInterface;

/**
 * No Barcode No Time.
 */
class NoResult implements ResultInterface
{
    public function __construct(
        private EventInterface $event,
        private int $position,
    ) {
    }

    public function getAthlete(): AthleteInterface
    {
        return new UnknownAthlete();
    }

    public function getEvent(): EventInterface
    {
        return $this->event;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getTime(): TimeInterface
    {
        return new NoTime();
    }

    public function getPersonalBest(): TimeInterface
    {
        return new NoTime();
    }

    public function getAgeGroup(): string
    {
        return '';
    }

    public function getAgeGrade(): float
    {
        return 0;
    }

    public function getAchievements(): string
    {
        return '';
    }
}
