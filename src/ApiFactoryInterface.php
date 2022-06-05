<?php

namespace parkrunScraper;

/**
 * Describes a factory for creating Api interfaces.
 */
interface ApiFactoryInterface
{
    public function create(?string $tmpDirectory = null): ApiInterface;
}
