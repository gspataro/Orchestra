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
 * @return void
 */

function recursiveDelete(string $path, bool $onlyContent = false): void
{
    if (!is_dir($path)) {
        return;
    }

    $directory = new DirectoryIterator($path);

    foreach ($directory as $item) {
        if ($item->isDot()) {
            continue;
        }

        if ($item->isFile()) {
            unlink($item->getPathname());
            continue;
        }

        recursiveDelete($item->getPathname());
    }

    if (!$onlyContent) {
        rmdir($path);
    }
}

/**
 * Join two paths together
 *
 * @param string $base
 * @param string $path
 * @return string
 */

function pathJoin(string $base, string $path): string
{
    $separator = null;

    if ($path && !str_ends_with($base, '/') && !str_starts_with($path, '/')) {
        $separator = DIRECTORY_SEPARATOR;
    }

    return $base . $separator . $path;
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
