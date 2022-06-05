<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Parser;

use parkrunScraper\ResultInterface;
use PHPUnit\Framework\TestCase;

class DomDocumentParserTest extends TestCase
{
    /**
     * @dataProvider dataParser
     */
    public function testParser(
        string $pageBody,
        \DateTimeImmutable $expectedDate,
        int $expectedEventNo,
        string $expectedLocation,
        int $expectedCount,
        ?int $expectedWinner,
        string $expectedAchievments
    ): void {
        $parser = new DomDocumentParser();

        $event = $parser->parseEvent($pageBody);
        $this->assertEquals($expectedDate, $event->getDate());
        $this->assertSame($expectedEventNo, $event->getNumber());
        $this->assertSame($expectedLocation, $event->getLocation()->getName());

        $summary = $parser->parseEventSummary($pageBody);
        $results = $summary->getResults();
        $this->assertCount($expectedCount, $results);
        /** @var ResultInterface $winner */
        $winner = $results->iterate()->current();
        $this->assertSame($expectedWinner, $winner->getAthlete()->getId());
        $this->assertSame(1, $winner->getPosition());
        $this->assertSame($expectedAchievments, $winner->getAchievements());
    }

    public function dataParser(): \Generator
    {
        yield 'Milton Keynes 1' => [
            file_get_contents(__DIR__ . '/fixtures/miltonkeynes-1.html'),
            new \DateTimeImmutable('2010-01-16'),
            1,
            'Milton Keynes parkrun',
            44,
            84,
            'First Timer!',
        ];
        yield 'Linford Wood 241' => [
            file_get_contents(__DIR__ . '/fixtures/linfordwood-241.html'),
            new \DateTimeImmutable('2022-05-28'),
            241,
            'Linford Wood parkrun',
            321,
            5035871,
            'New PB!',
        ];
        yield 'Milton Keynes 454' => [
            file_get_contents(__DIR__ . '/fixtures/miltonkeynes-454.html'),
            new \DateTimeImmutable('2018-12-15'),
            454,
            'Milton Keynes parkrun',
            346,
            null,
            '',
        ];
        yield 'Willoughby 76' => [
            file_get_contents(__DIR__ . '/fixtures/willoughby-76.html'),
            new \DateTimeImmutable('2018-04-07'),
            76,
            'Willoughby parkrun',
            237,
            1978702,
            '',
        ];
    }

    public function testInvalidParser(): void
    {
        $this->expectException(ParserException::class);
        $parser = new DomDocumentParser();
        $parser->parseEvent((string) file_get_contents(__DIR__ . '/fixtures/invalid-body.html'));
    }
}
