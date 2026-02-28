<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Assets\DriverInterface;
use Orchestra\Theme\Theme;

final class StaticDriver implements DriverInterface
{
    /** @var string[] */
    private array $css = [];

    /** @var string[] */
    private array $js = [];

    private function compileEntry(string $entry, string $outputPath): void
    {
        if (!is_file($entry)) {
            return;
        }

        $hash = hash('sha256', file_get_contents($entry));
        $signature = substr($hash, 0, 16);
        $filename = pathinfo($entry, PATHINFO_FILENAME);
        $extension = pathinfo($entry, PATHINFO_EXTENSION);

        $outputFilename = "{$filename}-{$signature}.{$extension}";
        $output = pathJoin($outputPath, $outputFilename);

        copy($entry, $output);

        switch ($extension) {
            case 'css':
                $this->css[] = $outputFilename;
                break;
            case 'js':
                $this->js[] = $outputFilename;
                break;
        }
    }

    public function build(Theme $theme, BuildContext $context): void
    {
        $entries = $theme->assets->entries;

        if (empty($entries)) {
            return;
        }

        $outputPath = $context->paths()->output('assets');

        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0777, true);
        } else {
            recursiveDelete($outputPath, true);
        }

        foreach ($entries as $entry) {
            $this->compileEntry(
                pathJoin($theme->path, pathJoin($theme->assets->dir, $entry)),
                $outputPath
            );
        }
    }

    public function css(): array
    {
        $css = [];

        foreach ($this->css as $entry) {
            $css[] = pathJoin('assets', $entry);
        }

        return $css;
    }

    public function js(): array
    {
        $js = [];

        foreach ($this->js as $entry) {
            $js[] = pathJoin('assets', $entry);
        }

        return $js;
    }
}
