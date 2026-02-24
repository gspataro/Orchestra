<?php

namespace Orchestra\Compiler;

use GSpataro\DependencyInjection\Container;
use Orchestra\Compiler\Runtime\CleanupRuntime;
use Orchestra\Compiler\Runtime\SitemapRuntime;
use Orchestra\Compiler\Runtime\ContentsRuntime;
use Orchestra\Compiler\Runtime\CreateContextRuntime;
use Orchestra\Compiler\Runtime\MediaRuntime;
use Orchestra\Compiler\Runtime\PagesRuntime;
use Orchestra\Compiler\Runtime\SchemasRuntime;

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
