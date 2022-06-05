<?php

namespace parkrunScraper\FileSystem;

/**
 * Describes a file system interface.
 */
interface FileSystemInterface
{
    public function writeFile(string $relativePath, string $content): bool;

    public function fileExits(string $relativePath): bool;

    public function readFile(string $relativePath): ?string;

    public function requireFile(string $relativePath): mixed;

    public function deleteFile(string $relativePath): bool;

    public function getFiles(string $relativeDirectoryPath): \Generator;
}
