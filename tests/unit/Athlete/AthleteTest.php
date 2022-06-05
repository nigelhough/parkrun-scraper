<?php

declare(strict_types=1);

namespace parkrunScraper\Athlete;

use PHPUnit\Framework\TestCase;

class AthleteTest extends TestCase
{
    /**
     * @dataProvider dataAccessors
     */
    public function testAccessors(
        int $id,
        string $name,
        int $runs,
        int $volunteers,
        ?string $club,
        string $gender
    ): void {
        $athlete = new Athlete(
            $id,
            $name,
            $runs,
            $volunteers,
            $club,
            $gender
        );

        $this->assertSame($id, $athlete->getId());
        $this->assertSame($runs, $athlete->getRuns());
        $this->assertSame($volunteers, $athlete->getVolunteers());
        $this->assertSame($club, $athlete->getClub());
        $this->assertSame($gender, $athlete->getGender());
    }

    public function dataAccessors(): \Generator
    {
        yield 'An Athlete' => [
            960984,
            'Nigel Hough',
            140,
            7,
            'Workforce Fit and Strong',
            'Male',
        ];
        yield 'Another athlete no club' => [
            123456,
            'Athlete',
            200,
            10,
            null,
            'Female',
        ];
        yield 'Empty Athlete' => [
            0,
            '',
            0,
            0,
            null,
            '',
        ];
    }

    /**
     * @dataProvider dataGetName
     */
    public function testGetName(string $name, string $expected): void
    {
        $athlete = new Athlete(
            0,
            $name,
            0,
            0,
            null,
            ''
        );

        $this->assertSame($expected, $athlete->getName());
        $this->assertSame($expected, (string) $athlete);
    }

    public function dataGetName(): \Generator
    {
        yield 'A name' => ['Nigel hough', 'Nigel Hough'];
        yield 'A single name' => ['katy', 'Katy'];
        yield 'A messy name' => ['A mIx Of ChArAcTeRs', 'A Mix Of Characters'];
        yield 'Including quotes' => ['jack O\'neill', 'Jack O\'neill'];
    }
}
