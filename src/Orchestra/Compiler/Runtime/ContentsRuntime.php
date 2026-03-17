<?php

namespace Orchestra\Compiler\Runtime;

use Exception;
use Orchestra\Content\ContentRepository;
use Orchestra\Content\ReadersCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Content\Cache\ContentCacheRepository;
use Orchestra\Content\Factory\ContentFactory;
use Orchestra\Content\Factory\SourceFactory;
use Orchestra\Content\Source;
use Orchestra\Project\Definition\Source\SourceDefinition;

final class ContentsRuntime extends Runtime
{
    private SourceFactory $sourceFactory;
    private ReadersCollection $readers;
    private ContentRepository $contents;
    private ContentFactory $contentFactory;
    private ContentCacheRepository $cache;

    /**
     * @param SourceDefinition $definition
     * @return Source[]
     */
    private function resolveSourcePath(SourceDefinition $definition): iterable
    {
        $paths = explode(';', $definition->path);

        foreach ($paths as $path) {
            $fullPath = $this->context->paths()->data($path);

            if (is_file($fullPath)) {
                yield $this->sourceFactory->fromDefinition($definition, $fullPath, $path, count($paths) > 1);
                continue;
            }

            if (str_contains($path, '*') || str_contains($path, '?') || str_contains($path, '[')) {
                $matches = glob($fullPath);

                if ($matches !== false) {
                    foreach ($matches as $match) {
                        $relativePath = substr($match, strlen($this->context->paths()->data()));
                        yield $this->sourceFactory->fromDefinition($definition, $match, $relativePath, true);
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
        $this->cache = $this->container->get('content.cache');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype()->sources() as $definition) {
            $this->output->print("Working on content group '{$definition->group}'");

            foreach ($this->resolveSourcePath($definition) as $source) {
                $fromCache = true;

                if (!$payload = $this->cache->load($source)) {
                    try {
                        $reader = $this->readers->get($source->reader);
                    } catch (Exception $e) {
                        $this->output->error(
                            "Reader '{$source->reader}' requested by contents group '{$definition->group}' not found."
                        );
                        return false;
                    }

                    $payload = $reader->compile($source);
                    $fromCache = false;
                }

                if (!$this->context->options()->ignoreDrafts || !$payload->metadata['draft']) {
                    $this->contents->add($this->contentFactory->fromPayload($payload));
                }

                if (!$fromCache) {
                    $this->cache->save($source, $payload);
                }
            }
        }

        return true;
    }
}
