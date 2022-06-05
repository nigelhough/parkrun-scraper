<?php

namespace parkrunScraper;

/**
 * Describes a parkrun location.
 */
interface LocationInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
