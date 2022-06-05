<?php

namespace parkrunScraper\parkrunWebsite\Request\Logger;

/**
 * Describes a request logger.
 */
interface RequestLoggerInterface
{
    public function log(string $requestKey, \DateTimeInterface $timestamp, array $context = []): void;

    public function getRequests(): \Generator;

    public function getLastRequestTime(string $requestKey): ?\DateTimeInterface;

    public function getLastRequestWithinRange(
        string $requestKey,
        \DateTimeInterface $earliestRequest
    ): ?\DateTimeInterface;
}
