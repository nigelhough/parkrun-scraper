<?php

declare(strict_types=1);

namespace parkrunScraper\Api;

use GuzzleHttp\Client;
use parkrunScraper\ApiInterface;
use parkrunScraper\ApiFactoryInterface;
use parkrunScraper\Cache\FileSystemCache;
use parkrunScraper\Calendar\Cancellations;
use parkrunScraper\Calendar\SaturdayAssumption;
use parkrunScraper\FileSystem\DirectoryBased;
use parkrunScraper\parkrunWebsite\Parser\DomDocumentParser;
use parkrunScraper\parkrunWebsite\Request\CachedRequest;
use parkrunScraper\parkrunWebsite\Request\GuzzleRequest;
use parkrunScraper\parkrunWebsite\Request\Logger\PHPLogger;
use parkrunScraper\parkrunWebsite\Request\RateLimitedRequest;
use TimeSource\System;

/**
 * An Api factory.
 * An implementation that directly creates objects when used as a library without DI.
 */
class Factory implements ApiFactoryInterface
{
    public function create(?string $tmpDirectory = null): ApiInterface
    {
        if ($tmpDirectory === null && is_dir(__DIR__ . '/../../tmp')) {
            // Running locally project has a local tmp directory
            $tmpDirectory = __DIR__ . '/../../tmp';
        } elseif ($tmpDirectory === null && is_dir(__DIR__ . '/../../tmp')) {
            // Running as a dependency does project has a local tmp directory
            $tmpDirectory = __DIR__ . '/../../../../../tmp';
        } elseif ($tmpDirectory === null) {
            // System temp
            $tmpDirectory = sys_get_temp_dir();
        }
        $parser = new DomDocumentParser();
        $timeSource = new System();
        $faker = \Faker\Factory::create();
        $fileSystem = new DirectoryBased($tmpDirectory);
        $requestLogger = new PHPLogger($fileSystem);
        return new Api(
            new CachedRequest(
                new FileSystemCache($fileSystem),
                new RateLimitedRequest(
                    $requestLogger,
                    $timeSource,
                    new GuzzleRequest(
                        $requestLogger,
                        $timeSource,
                        new Client(),
                        $faker->userAgent()
                    )
                ),
                $parser,
                new Cancellations(
                    new SaturdayAssumption()
                ),
                $timeSource
            ),
            $parser
        );
    }
}
