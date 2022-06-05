<?php

namespace parkrunScraper;

/**
 * Describes the Application Programmer Interface for the parkrun scraper library.
 */
interface ApiInterface
{
    public function getEventSummary(string $location, string $event): EventSummaryInterface;
}
