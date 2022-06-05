<?php

declare(strict_types=1);

namespace parkrunScraper\Athlete;

use parkrunScraper\AthleteInterface;

/**
 * A parkrun athlete. Runner/Volunteer.
 */
class Athlete implements AthleteInterface
{
    public function __construct(
        private int $id,
        private string $name,
        private int $runs,
        private int $volunteers,
        private ?string $club,
        private string $gender
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return ucwords(strtolower($this->name));
    }

    public function getRuns(): int
    {
        return $this->runs;
    }

    public function getVolunteers(): int
    {
        return $this->volunteers;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
