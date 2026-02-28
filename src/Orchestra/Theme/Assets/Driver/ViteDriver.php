<?php

namespace Orchestra\Theme\Assets\Driver;

use Orchestra\Compiler\BuildContext;
use Orchestra\Theme\Assets\DriverInterface;
use Orchestra\Theme\Theme;

final class ViteDriver implements DriverInterface
{
    private array $css = [];
    private array $js = [];

    public function build(Theme $theme, BuildContext $context): void
    {
        $manifest = pathJoin($theme->assets->dir, 'manifest.json');

        if (!is_file($manifest)) {
            return;
        }

        $data = json_decode(file_get_contents($manifest), true);

        if (empty($data)) {
            return;
        }

        foreach ($data as $input => $chunk) {
            if (!isset($chunk['isEntry']) || !$chunk['isEntry']) {
                continue;
            }

            $entry = pathJoin($theme->assets->dir, $chunk['file']);
            $extension = pathinfo($input, PATHINFO_EXTENSION);
            $output = pathJoin($context->paths()->output('assets'), $chunk['file']);

            copy($entry, $output);

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
