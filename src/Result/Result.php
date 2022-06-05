<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\AthleteInterface;
use parkrunScraper\EventInterface;
use parkrunScraper\ResultInterface;
use parkrunScraper\TimeInterface;

class Result implements ResultInterface
{
    public function __construct(
        private AthleteInterface $athlete,
        private EventInterface $event,
        private int $position,
        private TimeInterface $time,
        private TimeInterface $pb,
        private string $ageGroup,
        private float $ageGrade,
        private string $achievements
    ) {
    }

    public function getAthlete(): AthleteInterface
    {
        return $this->athlete;
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
        return $this->time;
    }

    public function getPersonalBest(): TimeInterface
    {
        return $this->pb;
    }

    public function getAgeGroup(): string
    {
        return $this->ageGroup;
    }

    public function getAgeGrade(): float
    {
        return $this->ageGrade;
    }

    public function getAchievements(): string
    {
        return $this->achievements;
    }
}
