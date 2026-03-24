<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Assets\DriverInterface;
use Orchestra\Theme\Theme;

final class ViteDriver implements DriverInterface
{
    /** @var string[] */
    private array $css = [];

    /** @var string[] */
    private array $js = [];

    public function build(Theme $theme, BuildContext $context): void
    {
        $manifest = pathJoin($theme->path, $theme->assets->dir, '.vite', 'manifest.json');

        if (!is_file($manifest)) {
            return;
        }

        $data = json_decode(file_get_contents($manifest), true);

        if (empty($data)) {
            return;
        }

        $currentChunks = [];

        foreach ($data as $input => $chunk) {
            $entry = pathJoin($theme->path, $theme->assets->dir, $chunk['file']);
            $output = $context->paths()->output('assets', $chunk['file']);
            $currentChunks[] = $output;

            copy($entry, $output);

            if (isset($chunk['isEntry']) && $chunk['isEntry']) {
                $extension = pathinfo($input, PATHINFO_EXTENSION);

                switch ($extension) {
                    case 'css':
                        $this->css[] = pathJoin('assets', $chunk['file']);
                        break;
                    case 'js':
                        $this->js[] = pathJoin('assets', $chunk['file']);
                        break;
                }
            }
        }

        recursiveDelete($context->paths()->output('assets'), true, $currentChunks);
    }

    public function css(): array
    {
        $css = [];

        foreach ($this->css as $entry) {
            $css[] = $entry;
        }

        return $css;
    }

    public function js(): array
    {
        $js = [];

        foreach ($this->js as $entry) {
            $js[] = $entry;
        }

        return $js;
    }
}
