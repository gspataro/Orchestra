<?php

namespace Orchestra\Compiler\Runtime;

use Orchestra\Content\ContentRepository;
use Orchestra\Content\ReadersCollection;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Project\Source\ResolvedSource;
use Orchestra\Project\Source\Source;

final class ContentsRuntime extends Runtime
{
    private readonly ReadersCollection $readers;
    private readonly ContentRepository $contents;

    /**
     * @param Source $source
     * @return ResolvedSource|ResolvedSource[]
     */
    private function resolveSourcePath(Source $source): ResolvedSource|array
    {
        $resolved = [];
        $paths = explode(';', $source->path);

        foreach ($paths as $path) {
            $fullPath = $this->context->paths->data($path);

            if (is_file($fullPath)) {
                $resolved[] = $source->withResolvedPaths($fullPath, $path);
                continue;
            }

            if (str_contains($path, '*') || str_contains($path, '?') || str_contains($path, '[')) {
                $matches = glob($fullPath);

                if ($matches !== false) {
                    foreach ($matches as $match) {
                        $relativePath = substr($match, strlen($this->context->paths->data()));
                        $resolved[] = $source->withResolvedPaths($match, $relativePath);
                    }
                }
            }
        }

        return count($resolved) > 1 ? $resolved : $resolved[0];
    }

    public function run(BuildOptions $options): bool
    {
        /** @var ReadersCollection */
        $this->readers = $this->container->get('content.readers');

        /** @var ContentRepository */
        $this->contents = $this->container->get('content.repository');

        $this->output->info('Processing contents');

        foreach ($this->context->prototype->sources() as $source) {
            $this->output->print("Working on content group '{$source->group}'");

            $resolvedSources = $this->resolveSourcePath($source);

            $reader = $this->readers->get($source->reader);
            $content = $reader->compile($resolvedSources);

            if (!is_array($content)) {
                $this->contents->add($content);
                continue;
            }

            foreach ($content as $single) {
                $this->contents->add($single);
            }
        }

        return true;
    }
}
