<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Assets\DriverInterface;
use Orchestra\Theme\Theme;

final class ViteDriver extends BaseDriver
{
    public function discover(Theme $theme): void
    {
        $manifest = pathJoin($theme->path, $theme->assets->dir, '.vite', 'manifest.json');

        if (!is_file($manifest)) {
            return;
        }

        $data = json_decode(file_get_contents($manifest), true);

        if (empty($data)) {
            return;
        }

        foreach ($data as $input => $chunk) {
            $entry = pathJoin($theme->assets->dir, $chunk['file']);
            $publicPath = $chunk['file'];

            $this->addEntry($entry, $publicPath, $chunk['isEntry'] ?? false);
        }
    }
}
