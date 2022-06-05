<?php

declare(strict_types=1);

namespace parkrunScraper\Location;

use parkrunScraper\LocationInterface;

class Location implements LocationInterface
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
