<?php

declare(strict_types=1);

namespace parkrunScraper\Calendar;

/**
 * An implementation of parkrun calendar handling known cancellations.
 */
class Cancellations implements CalendarInterface
{
    private array $knownCancellations;

    public function __construct(private CalendarInterface $fallback)
    {
        // @todo Add a source of cancellation data.
        $this->knownCancellations = [
            'miltonkeynes' => [
                '2022-05-28' => '2022-05-28',
            ],
        ];
    }

    private function isCancelled(string $location, \DateTimeInterface $date): bool
    {
        return isset($this->knownCancellations[$location][$date->format('Y-m-d')]);
    }

    public function isParkrunDay(string $location, \DateTimeInterface $date): bool
    {
        $isParkrunDay = $this->fallback->isParkrunDay($location, $date);
        if (!$isParkrunDay) {
            // If we know it's not a parkrun day then no need to check cancellations.
            return false;
        }

        return !$this->isCancelled($location, $date);
    }

    public function lastParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface
    {
        $lastParkrun = $this->fallback->lastParkrunDay($location, $date);
        while ($this->isCancelled($location, $lastParkrun)) {
            $lastParkrun = $this->fallback->lastParkrunDay($location, $lastParkrun);
        }
        return $lastParkrun;
    }

    public function nextParkrunDay(string $location, \DateTimeInterface $date): \DateTimeInterface
    {
        $nextParkrun = $this->fallback->nextParkrunDay($location, $date);
        while ($this->isCancelled($location, $nextParkrun)) {
            $nextParkrun = $this->fallback->nextParkrunDay($location, $nextParkrun);
        }
        return $nextParkrun;
    }
}
