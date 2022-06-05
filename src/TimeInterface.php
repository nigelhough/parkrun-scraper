<?php

namespace parkrunScraper;

/**
 * Describes a parkrun time, how long it took to run 5k.
 */
interface TimeInterface extends \Stringable
{
    public function getSeconds(): ?int;

    public function getFormatted(): ?string;
}
