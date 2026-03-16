<?php

/**
 * Recursively copy a file or directory to another location excluding the items in the $exclude array
 *
 * @param string $from
 * @param string $to
 * @param string[] $exclude
 * @return void
 */

function recursiveCopy(string $from, string $to, array $exclude = []): void
{
    if (!is_dir($to)) {
        mkdir($to, 0777, true);
    }

    $directory = new DirectoryIterator($from);

    foreach ($directory as $item) {
        if ($item->isDot()) {
            continue;
        }

        $source = $item->getPathname();
        $destination = $to . '/' . $item->getBasename();

        if (in_array($item->getBasename(), $exclude)) {
            continue;
        }

        if (is_file($source)) {
            copy($source, $destination);
            continue;
        }

        recursiveCopy($source, $destination, $exclude);
    }
}

/**
 * Recursively delete a directory and its content
 *
 * @param string $path
 * @param bool $onlyContent
 * @param string[] $exclude
 * @return void
 */

function recursiveDelete(string $path, bool $onlyContent = false, array $exclude = []): void
{
    if (!is_dir($path)) {
        return;
    }

    $directory = new DirectoryIterator($path);

    foreach ($directory as $item) {
        if ($item->isDot()) {
            continue;
        }

        if ($item->isFile() && !in_array($item->getPathname(), $exclude)) {
            unlink($item->getPathname());
            continue;
        }

        if ($item->isDir() && !in_array($item->getPathname(), $exclude)) {
            recursiveDelete($item->getPathname(), false, $exclude);
        }
    }

    $remainingItems = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

    if (!$onlyContent && !$remainingItems->valid()) {
        rmdir($path);
    }
}

/**
 * Join two paths together
 *
 * @param string $base
 * @param string $parts
 * @return string
 */

function pathJoin(string $base, string ...$parts): string
{
    $path = [
        rtrim($base, DIRECTORY_SEPARATOR)
    ];

    foreach ($parts as $part) {
        $path[] = trim($part, DIRECTORY_SEPARATOR);
    }

    return implode(DIRECTORY_SEPARATOR, $path);
}

/**
 * Add suffix to a file name
 *
 * @param string $filename
 * @param string $suffix
 * @return string
 */

function addSuffixToFilename(string $filename, string $suffix): string
{
    if (!str_contains($filename, '.')) {
        return $filename . $suffix;
    }

    $extensionPosition = strrpos($filename, '.');
    return substr($filename, 0, $extensionPosition) . $suffix . substr($filename, $extensionPosition);
}
