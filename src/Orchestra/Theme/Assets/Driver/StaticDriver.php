<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Theme\Theme;

final class StaticDriver extends BaseDriver
{
    public function discover(Theme $theme): void
    {
        $entries = $theme->assets->entries;

        if (empty($entries)) {
            return;
        }

        foreach ($entries as $entry) {
            $entryPath = pathJoin($theme->path, $theme->assets->dir, $entry);

            if (!is_file($entryPath)) {
                continue;
            }

            $hash = hash('sha256', file_get_contents($entryPath));
            $signature = substr($hash, 0, 16);
            $filename = pathinfo($entry, PATHINFO_FILENAME);
            $extension = pathinfo($entry, PATHINFO_EXTENSION);

            $publicPath = "{$filename}-{$signature}.{$extension}";

            $this->addEntry(
                pathJoin($theme->assets->dir, $entry),
                $publicPath
            );
        }
    }
}
