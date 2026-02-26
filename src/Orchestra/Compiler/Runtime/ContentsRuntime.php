<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Content\ReadersCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Project\Source\ResolvedSource;
use Orchestra\Project\Source\Source;

final class ContentsRuntime extends Runtime
{
    private readonly ReadersCollection $readers;
    private readonly ContentRepository $contents;
    private readonly ContentFactory $contentFactory;

    /**
     * @param Source $source
     * @return ResolvedSource[]
     */
    private function resolveSourcePath(Source $source): iterable
    {
        $paths = explode(';', $source->path);

        foreach ($paths as $path) {
            $fullPath = $this->context->paths->data($path);

            if (is_file($fullPath)) {
                yield $source->withResolvedPaths($fullPath, $path);
                continue;
            }

            if (str_contains($path, '*') || str_contains($path, '?') || str_contains($path, '[')) {
                $matches = glob($fullPath);

                if ($matches !== false) {
                    foreach ($matches as $match) {
                        $relativePath = substr($match, strlen($this->context->paths->data()));
                        yield $source->withResolvedPaths($match, $relativePath);
                    }
                }
            }
        }
    }

    public function run(BuildOptions $options): bool
    {
        $this->readers = $this->container->get('content.readers');
        $this->contents = $this->container->get('content.repository');
        $this->contentFactory = $this->container->get('content.factory');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype->sources() as $source) {
            $this->output->print("Working on content group '{$source->group}'");

            foreach ($this->resolveSourcePath($source) as $resolvedSource) {
                $reader = $this->readers->get($source->reader);

                foreach ($reader->compile($resolvedSource) as $payload) {
                    $this->contents->add($this->contentFactory->fromPayload($payload));
                }
            }
        }

        return true;
    }
}
