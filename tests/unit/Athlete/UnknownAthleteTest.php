<?php

declare(strict_types=1);

namespace parkrunScraper\Athlete;

use PHPUnit\Framework\TestCase;

class UnknownAthleteTest extends TestCase
{
    public function testAccessors(): void
    {
        $athlete = new UnknownAthlete();

        $this->assertNull($athlete->getId());
        $this->assertSame('Unknown', $athlete->getName());
        $this->assertSame('Unknown', (string) $athlete);
        $this->assertSame(0, $athlete->getRuns());
        $this->assertSame(0, $athlete->getVolunteers());
        $this->assertNull($athlete->getClub());
        $this->assertNull($athlete->getGender());
    }
}
