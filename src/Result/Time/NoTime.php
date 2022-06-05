<?php

declare(strict_types=1);

namespace parkrunScraper\Result\Time;

use parkrunScraper\TimeInterface;

/**
 * No barcode, no time.
 */
class NoTime implements TimeInterface
{
    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return '';
    }

    public function getSeconds(): ?int
    {
        return null;
    }

    public function getFormatted(): ?string
    {
        return null;
    }
}
