<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\ResultCollectionInterface;
use parkrunScraper\ResultInterface;

class ResultCollection implements ResultCollectionInterface
{
    public function __construct(private int $totalRunners, private \Generator $results)
    {
    }

    public function count(): int
    {
        return $this->totalRunners;
    }

    /**
     * @return \Generator|ResultInterface[]
     */
    public function iterate(): \Generator
    {
        yield from $this->results;
    }
}
