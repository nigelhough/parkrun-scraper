<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\Athlete\UnknownAthlete;
use parkrunScraper\EventInterface;
use parkrunScraper\Result\Time\NoTime;
use PHPUnit\Framework\TestCase;

class NoResultTest extends TestCase
{
    public function testGetAccessors(): void
    {
        $noResult = new NoResult(
            $event = $this->createMock(EventInterface::class),
            1
        );
        $this->assertEquals(new UnknownAthlete(), $noResult->getAthlete());
        $this->assertSame($event, $noResult->getEvent());
        $this->assertSame(1, $noResult->getPosition());
        $this->assertEquals(new NoTime(), $noResult->getTime());
        $this->assertEquals(new NoTime(), $noResult->getPersonalBest());
        $this->assertSame('', $noResult->getAgeGroup());
        $this->assertSame(0.0, $noResult->getAgeGrade());
        $this->assertSame('', $noResult->getAchievements());

        $noResult = new NoResult(
            $event,
            100
        );
        $this->assertSame(100, $noResult->getPosition());
    }
}
