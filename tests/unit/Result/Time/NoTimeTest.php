<?php

declare(strict_types=1);

namespace parkrunScraper\Result\Time;

use PHPUnit\Framework\TestCase;

class NoTimeTest extends TestCase
{
    public function testNoTime(): void
    {
        $noTime = new NoTime();
        $this->assertNull($noTime->getFormatted());
        $this->assertNull($noTime->getSeconds());
        $this->assertSame('', (string) $noTime);
    }
}
