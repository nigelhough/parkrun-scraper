<?php

declare(strict_types=1);

namespace parkrunScraper\Athlete;

use parkrunScraper\AthleteInterface;

/**
 * An unknown runner. No barcode, no time.
 */
class UnknownAthlete implements AthleteInterface
{
    public function getId(): ?int
    {
        return null;
    }

    public function getName(): string
    {
        return 'Unknown';
    }

    public function getRuns(): int
    {
        return 0;
    }

    public function getVolunteers(): int
    {
        return 0;
    }

    public function getClub(): ?string
    {
        return null;
    }

    public function getGender(): ?string
    {
        return null;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
