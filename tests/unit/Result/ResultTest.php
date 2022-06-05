<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\AthleteInterface;
use parkrunScraper\EventInterface;
use parkrunScraper\TimeInterface;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /**
     * @dataProvider dataAccessors
     */
    public function testAccessors(
        int $position,
        string $ageGroup,
        float $ageGrade,
        string $achievements
    ): void {
        $result = new Result(
            $athlete = $this->createMock(AthleteInterface::class),
            $event = $this->createMock(EventInterface::class),
            $position,
            $time = $this->createMock(TimeInterface::class),
            $pb = $this->createMock(TimeInterface::class),
            $ageGroup,
            $ageGrade,
            $achievements
        );

        $this->assertSame($athlete, $result->getAthlete());
        $this->assertSame($event, $result->getEvent());
        $this->assertSame($position, $result->getPosition());
        $this->assertSame($time, $result->getTime());
        $this->assertSame($pb, $result->getPersonalBest());
        $this->assertSame($ageGroup, $result->getAgeGroup());
        $this->assertSame($ageGrade, $result->getAgeGrade());
        $this->assertSame($achievements, $result->getAchievements());
    }

    public function dataAccessors(): \Generator
    {
        yield 'A Result' => [
            1,
            'VM35-39',
            50.5,
            'PB',
        ];
        yield 'Another Result' => [
            100,
            'JW11-14',
            70.3,
            '',
        ];
    }
}
