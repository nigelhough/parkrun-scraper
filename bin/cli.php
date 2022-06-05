#!/usr/bin/env php
<?php

/**
 * A CLI interface to the parkrun Scraper.
 * Used for making manual requests during testing of the scraping library.
 * @todo Consider updating to use a library like CLIMate.
 */
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// CLI Options.
$options = getopt("l::e::");
$location = $options['l'] ?? 'miltonkeynes';
$event = $options['e'] ?? 'latestresults';

try {
    $api = (new \parkrunScraper\Api\Factory())->create();

    $summary = $api->getEventSummary($location, $event);
    echo $summary->getEvent()->getLocation()->getName()
        . ", event="
        . $summary->getEvent()->getNumber()
        . " on "
        . $summary->getEvent()->getDate()->format('c')
        . "\n";

    foreach ($summary->getResults()->iterate() as $result) {
        if ($result->getAthlete()->getId() === 960984) {
            var_dump($result->getPosition());
            var_dump($result->getAthlete()->getId());
            var_dump($result->getAthlete()->getName());
            var_dump((string) $result->getTime());
        }
    }
} catch (\Throwable $e) {
    echo $e->getMessage();
    exit(1);
}

exit(0);
