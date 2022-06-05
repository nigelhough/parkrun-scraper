<?php

namespace parkrunScraper;

/**
 * Describes a parkrun result.
 * An athletes result for an event.
 */
interface ResultInterface
{
    public function getAthlete(): AthleteInterface;

    public function getEvent(): EventInterface;

    public function getPosition(): int;

    public function getTime(): TimeInterface;

    public function getPersonalBest(): TimeInterface;

    /**
     * @todo ENUM
     */
    public function getAgeGroup(): string;

    public function getAgeGrade(): float;

    /**
     * @todo Add BitWise Operator.
     */
    public function getAchievements(): string;
}
