<?php

/**
 * Recursively copy a file or directory to another location excluding the items in the $exclude array
 *
 * @param string $from
 * @param string $top
 * @param array $exclude
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
 * @return void
 */

function recursiveDelete(string $path): void
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

    rmdir($path);
}

/**
 * Get string between
 *
 * @param string $string
 * @param string $openTag
 * @param string $closeTag
 * @return string|null
 */

function getStringBetween(string $string, string $openTag, string $closeTag): ?string
{
    $start = strpos($string, $openTag);

    if ($start == 0) {
        return null;
    }

    $start += strlen($openTag);
    $end = strpos($string, $closeTag, $start) - $start;

    return substr($string, $start, $end);
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

    if (!str_ends_with($base, '/') && !str_starts_with($path, '/')) {
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
