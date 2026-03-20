<?php

namespace Orchestra\Compiler\Factory;

use GSpataro\DependencyInjection\Container;
use Orchestra\Compiler\BuildContext;
use Orchestra\Compiler\BuildOutputInterface;
use Orchestra\Compiler\Pipeline\BasePipeline;
use Orchestra\Compiler\PipelineInterface;

final class PipelineFactory
{
    public function __construct(
        private readonly Container $container
    ) {
    }

    /**
     * @param class-string<PipelineInterface> $pipeline
     * @param BuildContext $context
     * @param BuildOutputInterface $output
     * @return PipelineInterface
     */
    public function make(
        string $pipeline,
        BuildContext $context,
        BuildOutputInterface $output
    ): PipelineInterface {
        return new $pipeline(
            $this->container,
            $context,
            $output
        );
    }
}
