<?php

declare(strict_types=1);

namespace parkrunScraper\Event;

use parkrunScraper\EventInterface;
use parkrunScraper\ResultCollectionInterface;

/**
 * A parkrun event summary.
 */
class EventSummary implements \parkrunScraper\EventSummaryInterface
{
    public function __construct(private EventInterface $event, private ResultCollectionInterface $resultCollection)
    {
    }

    public function getEvent(): EventInterface
    {
        return $this->event;
    }

    public function getResults(): ResultCollectionInterface
    {
        return $this->resultCollection;
    }
}
