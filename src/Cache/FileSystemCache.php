<?php

declare(strict_types=1);

namespace parkrunScraper\Cache;

use parkrunScraper\FileSystem\FileSystemInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * A thrown together PSR-16 cache implementation. Not a stable long-term choice.
 * @todo Improve
 * @todo Use a better implementation
 * @todo Implement TTL
 */
class FileSystemCache implements CacheInterface
{
    public function __construct(private FileSystemInterface $fileSystem)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (!$this->fileSystem->fileExits("/cache/{$key}")) {
            return $default;
        }
        return $this->fileSystem->readFile("/cache/{$key}");
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        return $this->fileSystem->writeFile("/cache/{$key}", (string) $value);
    }

    public function delete(string $key): bool
    {
        return $this->fileSystem->deleteFile("/cache/{$key}");
    }

    public function clear(): bool
    {
        foreach ($this->fileSystem->getFiles("/cache/") as $file) {
            if (!unlink($file)) {
                return false;
            }
        }
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $index => $key) {
            yield $index => $this->get($key, $default);
        }
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        return $this->fileSystem->fileExits("/cache/{$key}");
    }
}
