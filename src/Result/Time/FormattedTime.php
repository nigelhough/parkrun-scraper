<?php

declare(strict_types=1);

namespace parkrunScraper\Result\Time;

use parkrunScraper\TimeInterface;

/**
 * A time implementation that can be created from a formatted string.
 */
class FormattedTime implements TimeInterface
{
    public function __construct(private string $formattedTime)
    {
    }

    public function __toString(): string
    {
        return (string) $this->getFormatted();
    }

    public function getSeconds(): ?int
    {
        [$seconds, $minutes, $hours] = array_map(
            'intval',
            array_map('strrev', explode(':', strrev($this->formattedTime) . ':00:00'))
        );
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    public function getFormatted(): ?string
    {
        return $this->formattedTime;
    }
}
