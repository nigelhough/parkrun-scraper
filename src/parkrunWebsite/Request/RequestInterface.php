<?php

namespace parkrunScraper\parkrunWebsite\Request;

/**
 * Describes a request that can be made to the parkrun website.
 */
interface RequestInterface
{
    public function getEventSummary(string $location, string $event): string|\Stringable;
}
