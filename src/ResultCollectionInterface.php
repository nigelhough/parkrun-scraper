<?php

declare(strict_types=1);

namespace parkrunScraper;

/**
 * Describes a collection of results.
 * This could be all the results at an event, all the results for an athlete or anything that represents
 * multiple results.
 */
interface ResultCollectionInterface extends \Countable
{
    /**
     * @return \Generator|ResultInterface[]
     */
    public function iterate(): \Generator;
}
