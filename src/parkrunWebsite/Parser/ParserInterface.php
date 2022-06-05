<?php

namespace parkrunScraper\parkrunWebsite\Parser;

use parkrunScraper\EventInterface;
use parkrunScraper\EventSummaryInterface;

/**
 * Describes a parser able to parse response from the parkrun website.
 */
interface ParserInterface
{
    public function parseEvent(string $pageBody): EventInterface;

    public function parseEventSummary(string $pageBody): EventSummaryInterface;
}
