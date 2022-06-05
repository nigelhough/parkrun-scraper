<?php

namespace parkrunScraper;

/**
 * Describes a parkrun event.
 */
interface EventSummaryInterface
{
    public function getEvent(): EventInterface;

    public function getResults(): ResultCollectionInterface;
}
