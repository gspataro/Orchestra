<?php

namespace GSpataro\Assets;

use GSpataro\Assets\Exception\InvalidViteManifestException;

class Vite
{
    private array $manifest = [];

    public function __construct(
        private readonly string $manifestPath,
        private readonly string $outputPath
    ) {
    }

    /**
     * Load vite manifest
     *
     * @return void
     */

    public function loadManifest(): void
    {
        if (!is_file($this->manifestPath)) {
            throw new InvalidViteManifestException(
                "Vite manifest not found: {$this->manifestPath}"
            );
        }

        $manifest = file_get_contents($this->manifestPath);

        if (!json_validate($manifest)) {
            throw new InvalidViteManifestException(
                "Invalid vite manifest format: {$this->manifestPath}"
            );
        }

        $this->manifest = json_decode($manifest, true);
    }

    /**
     * Generate CSS tags
     *
     * @return string
     */

    public function css()
    {
        $tags = [];

        foreach ($this->manifest as $input => $chunk) {
            if (!isset($chunk['isEntry']) || !$chunk['isEntry']) {
                continue;
            }

            $extension = pathinfo($input, PATHINFO_EXTENSION);

            if ($extension !== 'css') {
                continue;
            }

            $tags[] = '<link href="' . $this->outputPath . $chunk['file'] . '" rel="stylesheet">';
        }

        return implode("\n", $tags);
    }

    /**
     * Generate JS tags
     *
     * @return string
     */

    public function js()
    {
        $tags = [];

        foreach ($this->manifest as $input => $chunk) {
            if (!isset($chunk['isEntry']) || !$chunk['isEntry']) {
                continue;
            }

            $extension = pathinfo($input, PATHINFO_EXTENSION);

            if ($extension !== 'js') {
                continue;
            }

            $tags[] = '<script src="' . getenv('WEBSITE_URL') . $this->outputPath . $chunk['file'] . '"></script>';
        }

        return implode("\n", $tags);
    }
}
