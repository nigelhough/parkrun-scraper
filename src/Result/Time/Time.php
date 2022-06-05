<?php

declare(strict_types=1);

namespace parkrunScraper\Result\Time;

use parkrunScraper\TimeInterface;

/**
 * A time implementation that can be created from seconds.
 */
class Time implements TimeInterface
{
    public function __construct(private int $seconds)
    {
    }

    public function __toString(): string
    {
        return (string) $this->getFormatted();
    }

    public function getSeconds(): ?int
    {
        return $this->seconds;
    }

    public function getFormatted(): ?string
    {
        $seconds = $this->seconds;
        $hours = (int) floor($seconds / 3600);
        $seconds -= ($hours * 3600);
        $minutes = (int) floor($seconds / 60);
        $seconds -= ($minutes * 60);

        $formatted = str_pad((string) $hours, 2, '0', STR_PAD_LEFT)
            . ':'
            . str_pad((string) $minutes, 2, '0', STR_PAD_LEFT)
            . ':'
            . str_pad((string) $seconds, 2, '0', STR_PAD_LEFT);

        return $this->seconds < 3600 ? substr($formatted, 3) : $formatted;
    }
}
