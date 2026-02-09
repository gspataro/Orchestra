<?php

namespace Orchestra\Console\Runtime;

use DirectoryIterator;
use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Sitemap;

class RuntimeCleanup extends Runtime
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly Sitemap $sitemap
    ) {
    }

    private function cleanup(string $directory): void
    {
        if ($directory === $this->context->paths->output()) {
            $this->output->print('{bold}Cleaning up');
        }

        $sitemap = array_values($this->sitemap->getAll());
        $outputDirectory = new DirectoryIterator($directory);
        $excluded = ['.vite', 'assets', '.htaccess', 'sitemap.xml', 'favicon.png', 'favicon-dark.png', 'media'];

        foreach ($outputDirectory as $item) {
            if ($item->isDot()) {
                continue;
            }

            if (in_array($item->getBasename(), $excluded)) {
                continue;
            }

            $itemPath = $item->isFile()
                ? substr($item->getPathname(), strlen($this->context->paths->output()), strlen('.html') * -1)
                : substr($item->getPathname(), strlen($this->context->paths->output()));

            if ($item->isFile() && !in_array($itemPath, $sitemap)) {
                unlink($item->getPathname());
                continue;
            }

            if ($item->isDir()) {
                $this->cleanup($item->getPathname());
            }
        }
    }

    public function main(): mixed
    {
        $this->cleanup($this->context->paths->output());

        return true;
    }
}
