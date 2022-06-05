<?php

namespace parkrunScraper\Calendar;

/**
 * Describes a parkrun calendar able to get parkrun date specific data.
 */
interface CalendarInterface
{
    public function isParkrunDay(string $location, \DateTimeInterface $date): bool;

    public function lastParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface;

    public function nextParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface;
}
