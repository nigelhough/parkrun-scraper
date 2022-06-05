<?php

namespace parkrunScraper;

/**
 * Describes a parkrun Athlete.
 */
interface AthleteInterface extends \Stringable
{
    public function getId(): ?int;

    public function getName(): string;

    public function getRuns(): int;

    public function getVolunteers(): int;

    public function getClub(): ?string;

    /**
     * @todo ENUM
     */
    public function getGender(): ?string;
}
