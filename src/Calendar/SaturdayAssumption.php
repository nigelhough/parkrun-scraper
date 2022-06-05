<?php

declare(strict_types=1);

namespace parkrunScraper\Calendar;

/**
 * An implementation of the parkrun calendar making assumptions that parkruns are every saturday.
 */
class SaturdayAssumption implements CalendarInterface
{
    public function isParkrunDay(string $location, \DateTimeInterface $date): bool
    {
        // Assume Saturdays are a parkrun day.
        return $date->format('w') === '6';
    }

    public function lastParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface
    {
        return (\DateTimeImmutable::createFromInterface($date))->modify('last saturday');
    }

    public function nextParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface
    {
        return (\DateTimeImmutable::createFromInterface($date))->modify('next saturday');
    }
}
