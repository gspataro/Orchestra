<?php

namespace Orchestra\Compiler\Pipeline;

use GSpataro\DependencyInjection\Container;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOptions;
use Orchestra\Compiler\BuildOutputInterface;
use Orchestra\Compiler\PipelineInterface;

abstract class BasePipeline implements PipelineInterface
{
    /** @var array<class-string<\Orchestra\Compiler\RuntimeInterface>> */
    protected array $runtimes = [];

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
