<?php

declare(strict_types=1);

namespace parkrunScraper\Result;

use parkrunScraper\ResultInterface;
use PHPUnit\Framework\TestCase;

class ResultCollectionTest extends TestCase
{
    /**
     * @dataProvider dataResultCollection
     */
    public function testResultCollection(int $count, array $results): void
    {
        $resultCollection = new ResultCollection(
            $count,
            (function (array $results): \Generator {
                foreach ($results as $result) {
                    yield $result;
                }
            })(
                $results
            )
        );

        $this->assertSame($count, $resultCollection->count());
        $this->assertSame($results, iterator_to_array($resultCollection->iterate()));
    }

    public function dataResultCollection(): \Generator
    {
        yield 'No results' => [0, []];
        yield 'A Result' => [1, [$this->createMock(ResultInterface::class)]];
        yield 'More Result' => [100,
            [
                $this->createMock(ResultInterface::class),
                $this->createMock(ResultInterface::class),
                $this->createMock(ResultInterface::class),
            ],
        ];
    }
}
