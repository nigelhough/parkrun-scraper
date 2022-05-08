<?php

namespace parkrunScraper\parkrunWebsite\Request;

/**
 * Describes a request that can be made to the parkrun website.
 */
interface RequestInterface
{
    public function makeRequest(string $location, string $event): string|\Stringable;
}
