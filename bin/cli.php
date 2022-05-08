<?php

/**
 * A CLI interface to the parkrun Scraper.
 * Used for making manual requests during testing of the scraping library.
 * @todo Update to use a library like CLIMate.
 */
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// CLI Options.
$options = getopt("l::e::");
$location = $options['l'] ?? 'miltonkeynes';
$event = $options['e'] ?? 'latestresults';

try {
    // @todo Use the Interface and DI to abstract the implementation.
    $request = new \parkrunScraper\parkrunWebsite\Request\GuzzleRequest();
    $body = $request->makeRequest($location, $event);
    echo substr((string) $body, 0, 200);
} catch(\Throwable $e)
{
    echo $e->getMessage();
    exit(1);
}

exit(0);
