<?php

namespace parkrunScraper;

/**
 * Describes a parkrun event.
 * An event being an occurrence of a parkrun at a location.
 */
interface EventInterface
{
    public const LATEST_EVENT = 'latestresults';

    public function getLocation(): LocationInterface;

    public function getNumber(): int;

    public function getDate(): \DateTimeInterface;
}
