<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request\Logger;

use parkrunScraper\FileSystem\FileSystemInterface;

/**
 * An implementation of a request logger using a tmp PHP file.
 */
class PHPLogger implements RequestLoggerInterface
{
    public function __construct(private FileSystemInterface $fileSystem)
    {
    }

    public function log(string $requestKey, \DateTimeInterface $time, array $context = []): void
    {
        $logs = $this->fileSystem->requireFile('request-log.php') ?? [];
        $logs[] = ['key' => $requestKey, 'timestamp' => $time->getTimestamp(), 'context' => $context];
        $this->fileSystem->writeFile(
            'request-log.php',
            "<?php\nreturn " . var_export($logs, true) . ";"
        );
    }

    public function getRequests(): \Generator
    {
        $logs = $this->fileSystem->requireFile('request-log.php') ?? [];
        foreach (array_reverse($logs) as $log) {
            yield $log;
        }
    }

    public function getLastRequestTime(string $requestKey): ?\DateTimeInterface
    {
        foreach ($this->getRequests() as $log) {
            if ($log['key'] === $requestKey) {
                return (new \DateTimeImmutable())->setTimestamp($log['timestamp']);
            }
        }

        return null;
    }

    public function getLastRequestWithinRange(
        string $requestKey,
        \DateTimeInterface $earliestRequest
    ): ?\DateTimeInterface {
        foreach ($this->getRequests() as $log) {
            if ((int) $log['timestamp'] < $earliestRequest->getTimestamp()) {
                // Stop searching when gone past the earliest time.
                break;
            }
            if ($log['key'] === $requestKey) {
                return (new \DateTimeImmutable())->setTimestamp($log['timestamp']);
            }
        }

        return null;
    }
}
