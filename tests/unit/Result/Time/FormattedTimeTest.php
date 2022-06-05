<?php

declare(strict_types=1);

namespace parkrunScraper\Result\Time;

use PHPUnit\Framework\TestCase;

class FormattedTimeTest extends TestCase
{
    /**
     * @dataProvider dataFormattedTime
     */
    public function testFormattedTime(string $formattedTime, int $expectedSeconds): void
    {
        $time = new FormattedTime($formattedTime);

        $this->assertSame($expectedSeconds, $time->getSeconds());
        $this->assertSame($formattedTime, $time->getFormatted());
        $this->assertSame($formattedTime, (string) $time);
    }

    public function dataFormattedTime(): \Generator
    {
        yield 'No Seconds' => ['00:00', 0];
        yield '1 Second' => ['00:01', 1];
        yield '7 Second' => ['00:07', 7];
        yield '1 Minute' => ['01:00', 60];
        yield '2 Minute and 13 seconds' => ['02:13', 133];
        yield '10 minutes' => ['10:00', 600];
        yield '1 hour' => ['01:00:00', 3600];
        yield '1 hour plus' => ['01:01:01', 3661];
    }
}
