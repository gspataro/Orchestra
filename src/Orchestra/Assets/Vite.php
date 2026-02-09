<?php

namespace Orchestra\Assets;

use Orchestra\Assets\Exception\InvalidViteManifestException;

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
            $this->manifest = [];
            return;
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

    public function css(): string
    {
        if (empty($this->manifest)) {
            return '';
        }

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

    public function js(): string
    {
        if (empty($this->manifest)) {
            return '';
        }

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
