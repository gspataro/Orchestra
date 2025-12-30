<?php

namespace GSpataro\Application\Process;

use DirectoryIterator;
use GSpataro\Project\Sitemap;

class ProcessCleanup extends Process
{
    public function __construct(
        private readonly Sitemap $sitemap
    ) {
    }

    private function cleanup($directory = DIR_OUTPUT): void
    {
        if ($directory === DIR_OUTPUT) {
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
                ? substr($item->getPathname(), strlen(DIR_OUTPUT), strlen('.html') * -1)
                : substr($item->getPathname(), strlen(DIR_OUTPUT));

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
        $this->cleanup();

        return true;
    }
}
