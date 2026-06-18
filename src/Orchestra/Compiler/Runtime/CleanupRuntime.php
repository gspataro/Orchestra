<?php

namespace Orchestra\Compiler\Runtime;

use DirectoryIterator;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Publisher\OutputRegistry;

final class CleanupRuntime extends Runtime
{
    private OutputRegistry $outputRegistry;

    private function cleanup(string $directory): void
    {
        if ($directory === $this->context->paths()->output()) {
            $this->output->info('Cleaning up');
        }

        $outputDirectory = new DirectoryIterator($directory);

        $excluded = array_merge(
            $this->context->prototype()->configs()->get('orchestra.cleanup'),
            ['.htaccess', 'media', 'assets']
        );

        foreach ($outputDirectory as $item) {
            if ($item->isDot()) {
                continue;
            }

            if (in_array($item->getBasename(), $excluded)) {
                continue;
            }

            $itemPath = substr($item->getPathname(), strlen($this->context->paths()->output()));

            if ($item->isFile() && !in_array($itemPath, $this->outputRegistry->all())) {
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
        $this->outputRegistry = $this->container->get('publisher.registry');

        $this->cleanup($this->context->paths()->output());

        return true;
    }
}
