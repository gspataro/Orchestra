<?php

namespace Orchestra\Compiler\Runtime;

use DirectoryIterator;
use Orchestra\Compiler\BuildOptions;

final class CleanupRuntime extends Runtime
{
    private function cleanup(string $directory): void
    {
        if ($directory === $this->context->paths()->output()) {
            $this->output->info('Cleaning up');
        }

        $outputDirectory = new DirectoryIterator($directory);

        $excluded = array_merge(
            $this->context->prototype()->configs()->get('orchestra.cleanup'),
            ['.htaccess', 'media']
        );

        foreach ($outputDirectory as $item) {
            if ($item->isDot()) {
                continue;
            }

            if (in_array($item->getBasename(), $excluded)) {
                continue;
            }

            $itemPath = $item->isFile()
                ? substr($item->getPathname(), strlen($this->context->paths()->output()), strlen('.html') * -1)
                : substr($item->getPathname(), strlen($this->context->paths()->output()));

            if ($item->isFile() && !$this->context->sitemap()->fromPermalink($itemPath)) {
                unlink($item->getPathname());
                continue;
            }

            if ($item->isDir()) {
                $this->cleanup($item->getPathname());
            }
        }
    }

    public function run(BuildOptions $options): bool
    {
        $this->cleanup($this->context->paths()->output());

        return true;
    }
}
