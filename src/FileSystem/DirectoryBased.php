<?php

declare(strict_types=1);

namespace parkrunScraper\FileSystem;

/**
 * An implementation of the filesystem that uses the a root directory for file operations.
 */
class DirectoryBased implements FileSystemInterface
{
    public function __construct(private ?string $rootDirectory = null)
    {
        if (empty($this->rootDirectory)) {
            $this->rootDirectory = sys_get_temp_dir();
        }
        $this->rootDirectory .= '/parkrun-scraper';
    }

    public function writeFile(string $relativePath, string $content): bool
    {
        $filepath = $this->rootDirectory . '/' . ltrim($relativePath, '/');
        if (!is_dir(dirname($filepath))) {
            \mkdir(dirname($filepath), 0777, true);
        }
        return file_put_contents($filepath, $content) !== false;
    }

    public function readFile(string $relativePath): ?string
    {
        if (!$this->fileExits($relativePath)) {
            return null;
        }
        $filepath = $this->rootDirectory . '/' . ltrim($relativePath, '/');
        return file_get_contents($filepath) ?: '';
    }

    public function fileExits(string $relativePath): bool
    {
        $filepath = $this->rootDirectory . '/' . ltrim($relativePath, '/');
        return file_exists($filepath);
    }

    public function requireFile(string $relativePath): mixed
    {
        if (!$this->fileExits($relativePath)) {
            return null;
        }
        $filepath = $this->rootDirectory . '/' . ltrim($relativePath, '/');
        return require $filepath;
    }

    public function deleteFile(string $relativePath): bool
    {
        if (!$this->fileExits($relativePath)) {
            return true;
        }
        $filepath = $this->rootDirectory . '/' . ltrim($relativePath, '/');
        return unlink($filepath);
    }

    public function getFiles(string $relativeDirectoryPath): \Generator
    {
        $directoryPath = $this->rootDirectory . '/' . ltrim($relativeDirectoryPath, '/');
        foreach (array_diff((array) scandir($directoryPath), ['.', '..']) as $file) {
            yield $file;
        }
    }
}
