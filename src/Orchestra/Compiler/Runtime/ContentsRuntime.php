<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Content\ReadersCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Content\Factory\SourceFactory;
use Orchestra\Content\Source;
use Orchestra\Project\Definition\Source\SourceDefinition;

final class ContentsRuntime extends Runtime
{
    private readonly SourceFactory $sourceFactory;
    private readonly ReadersCollection $readers;
    private readonly ContentRepository $contents;
    private readonly ContentFactory $contentFactory;

    /**
     * @param SourceDefinition $definition
     * @return Source[]
     */
    private function resolveSourcePath(SourceDefinition $definition): iterable
    {
        $paths = explode(';', $definition->path);

        foreach ($paths as $path) {
            $fullPath = $this->context->paths->data($path);

            if (is_file($fullPath)) {
                yield $this->sourceFactory->fromDefinition($definition, $fullPath, $path);
                continue;
            }

            if (str_contains($path, '*') || str_contains($path, '?') || str_contains($path, '[')) {
                $matches = glob($fullPath);

                if ($matches !== false) {
                    foreach ($matches as $match) {
                        $relativePath = substr($match, strlen($this->context->paths->data()));
                        yield $this->sourceFactory->fromDefinition($definition, $match, $relativePath);
                    }
                }
            }
        }
    }

    public function run(BuildOptions $options): bool
    {
        $this->readers = $this->container->get('content.readers');
        $this->sourceFactory = $this->container->get('content.source.factory');
        $this->contents = $this->container->get('content.repository');
        $this->contentFactory = $this->container->get('content.factory');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype->sources() as $definition) {
            $this->output->print("Working on content group '{$definition->group}'");

            foreach ($this->resolveSourcePath($definition) as $source) {
                $reader = $this->readers->get($source->reader);

                foreach ($reader->compile($source) as $payload) {
                    $this->contents->add($this->contentFactory->fromPayload($payload));
                }
            }
        }

        return true;
    }
}
