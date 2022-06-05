<?php

declare(strict_types=1);

namespace parkrunScraper\Api;

use parkrunScraper\ApiInterface;
use parkrunScraper\EventSummaryInterface;
use parkrunScraper\parkrunWebsite\Parser\ParserInterface;
use parkrunScraper\parkrunWebsite\Request\RequestInterface;
use parkrunScraper\ScraperExceptionInterface;

/**
 * The programming interface to the parkrun scraper.
 */
class Api implements ApiInterface
{
    public function __construct(private RequestInterface $request, private ParserInterface $parser)
    {
    }

    /**
     * @throws ScraperExceptionInterface
     */
    public function getEventSummary(string $location, string $event): EventSummaryInterface
    {
        return $this->parser->parseEventSummary((string) $this->request->getEventSummary($location, $event));
    }
}
