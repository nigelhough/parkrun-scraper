<?php

declare(strict_types=1);

namespace parkrunScraper\Location;

use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    /**
     * @dataProvider dataGetName
     */
    public function testGetName(string $name): void
    {
        $location = new Location($name);
        $this->assertSame($name, $location->getName());
    }

    public function dataGetName(): \Generator
    {
        yield 'A name' => ['Milton Keynes'];
        yield 'Another name' => ['Linford Wood'];
        yield 'Empty name' => [''];
    }
}
