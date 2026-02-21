<?php

namespace Orchestra\Pipeline;

use GSpataro\DependencyInjection\Container;
use Orchestra\Pipeline\Runtime\CleanupRuntime;
use Orchestra\Pipeline\Runtime\SitemapRuntime;
use Orchestra\Pipeline\Runtime\ContentsRuntime;
use Orchestra\Pipeline\Runtime\CreateContextRuntime;
use Orchestra\Pipeline\Runtime\MediaRuntime;
use Orchestra\Pipeline\Runtime\PagesRuntime;
use Orchestra\Pipeline\Runtime\SchemasRuntime;

final class Pipeline
{
    /** @var Runtime[] */
    private array $runtimes = [
        CreateContextRuntime::class,
        ContentsRuntime::class,
        SchemasRuntime::class,
        PagesRuntime::class,
        MediaRuntime::class,
        SitemapRuntime::class,
        CleanupRuntime::class
    ];

    public function __construct(
        private readonly Container $container,
        private readonly BuildContext $context,
        private readonly BuildOutputInterface $output
    ) {
    }

    public function run(BuildOptions $options): bool
    {
        foreach ($this->runtimes as $runtime) {
            $runtime = new $runtime($this->container, $this->context, $this->output);
            $result = $runtime->run($options);

            if (!$result) {
                return false;
            }
        }

        return true;
    }
}
